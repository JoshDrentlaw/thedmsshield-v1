<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\Organization;
use App\Models\CompendiumItem;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Debug\Debug;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

use App\Broadcasting\CompendiumChannel;
use App\Events\OrganizationUpdate;

class OrganizationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaign_url)
    {
        if (!$campaign_url) {
            Redirect::to($_SERVER['HTTP_REFERER']);
        }
        $campaign = Campaign::firstWhere('url', $campaign_url);
        $organizations = Organization::all()->where('campaign_id', $campaign->id);
        return view('organizations.index', compact('campaign', 'organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = false;
        if (isset($_GET['map'])) {
            $map = true;
        }
        return view('organizations.create', compact('map'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $organization = CompendiumItem::storeCompendiumItem(new Organization, $request);
        $organizationUpdate = collect([
            'campaign_id' => $organization->campaign_id,
            'id' => $organization->id,
            'type' => 'newOrganization',
            'organization' => $organization
        ]);
        broadcast(new OrganizationUpdate($organizationUpdate))->toOthers();
        return ['status' => 200, 'item' => $organization];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($campaign_id, $organization_id)
    {
        if (!Auth::check()) return redirect('/');

        $organization = Organization::firstWhere('url', $organization_id);
        $campaign = $organization->campaign;

        if ($campaign_id !== $campaign->url) return redirect('/');
        if (Gate::denies('campaign-member', $campaign)) return redirect('/');

        $lastUpdated = $organization->updated_at;

        $dm = $campaign->dm;
        $user = Auth::user();

        return view('organizations.show', [
            'organization' => $organization,
            'dm' => $dm,
            'campaign' => $campaign,
            'isDm' => $user->id === $dm->id,
            'uri' => $_SERVER['REQUEST_URI'],
            'lastUpdated' => $lastUpdated
        ]);
    }

    public function show_component(Request $request)
    {
        extract($request->post());
        return [
            'status' => 200,
            'showComponent' => CompendiumItem::showComponent(Organization::find($id), 'organization')
        ];
    }

    public function show_to_players($id)
    {
        $organization = Organization::find($id);
        $organizationUpdate = collect([
            'campaign_id' => $organization->campaign_id,
            'id' => $organization->id,
            'type' => 'showToPlayers',
            'markerless' => $organization->markerless,
            'markerId' => !$organization->markerless ? $organization->marker->id : false
        ]);
        broadcast(new OrganizationUpdate($organizationUpdate))->toOthers();
        return ['status' => 200];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        $res = ['status' => 200];
        $post = $request->post();
        $organizationUpdate = collect([
            'campaign_id' => $organization->campaign_id,
            'id' => $organization->id,
            'type' => $post['type']
        ]);
        if ($post['type'] === 'edit') {
            $item = new CompendiumItem();
            $organization = $item::updateCompendiumItem($request, $organization);
            $res['updated_at'] = $organization->updated_at;
            $res['redirect'] = $item::$redirect;
            $organizationUpdate->put('name', $organization->name);
            $organizationUpdate->put('body', $organization->body);
        } elseif ($post['type'] === 'visibility') {
            $organization->update(['visible' => $post['visible']]);
            $organizationUpdate->put('visible', $post['visible']);
            $organizationUpdate->put('hasMarker', !$organization->markerless);
            if (!$organization->markerless) {
                $organizationUpdate->put('marker', $organization->marker);
                $organizationUpdate->put('markerVisible', $organization->marker->visible);
            }
            $res = true;
        }
        broadcast(new OrganizationUpdate($organizationUpdate))->toOthers();
        return $res;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}