<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\Creature;
use App\Models\CompendiumItem;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Debug\Debug;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

use App\Broadcasting\CompendiumChannel;
use App\Events\CreatureUpdate;

class CreaturesController extends Controller
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
        $creatures = Creature::all()->where('campaign_id', $campaign->id);
        return view('creatures.index', compact('campaign', 'creatures'));
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
        return view('creatures.create', compact('map'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $creature = CompendiumItem::storeCompendiumItem(new Creature, $request);
        $creatureUpdate = collect([
            'campaign_id' => $creature->campaign_id,
            'id' => $creature->id,
            'type' => 'newCreature',
            'creature' => $creature
        ]);
        broadcast(new CreatureUpdate($creatureUpdate))->toOthers();
        return ['status' => 200, 'item' => $creature];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($campaign_id, $creature_id)
    {
        if (!Auth::check()) return redirect('/');

        $creature = Creature::firstWhere('url', $creature_id);
        $campaign = $creature->campaign;

        if ($campaign_id !== $campaign->url) return redirect('/');
        if (Gate::denies('campaign-member', $campaign)) return redirect('/');

        $lastUpdated = $creature->updated_at;

        $dm = $campaign->dm;
        $user = Auth::user();

        return view('creatures.show', [
            'creature' => $creature,
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
        $item = Creature::find($id);
        $itemType = 'creature';
        $lastUpdated = $item->updated_at;
        $onMap = Str::contains($_SERVER['HTTP_REFERER'], 'maps');
        $showComponent = view('components.compendium-item', compact('item', 'itemType', 'isDm', 'lastUpdated', 'onMap'))->render();
        return ['status' => 200, 'showComponent' => $showComponent];
    }

    public function show_to_players($id)
    {
        $creature = Creature::find($id);
        $creatureUpdate = collect([
            'campaign_id' => $creature->campaign_id,
            'id' => $creature->id,
            'type' => 'showToPlayers',
            'markerless' => $creature->markerless,
            'markerId' => !$creature->markerless ? $creature->marker->id : false
        ]);
        broadcast(new CreatureUpdate($creatureUpdate))->toOthers();
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
     * @param  App\Models\Creature $creature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Creature $creature)
    {
        $res = ['status' => 200];
        $post = $request->post();
        $creatureUpdate = collect([
            'campaign_id' => $creature->campaign_id,
            'id' => $creature->id,
            'type' => $post['type']
        ]);
        if ($post['type'] === 'edit') {
            $item = new CompendiumItem();
            $creature = $item::updateCompendiumItem($request, $creature);
            $res['updated_at'] = $creature->updated_at;
            $res['redirect'] = $item::$redirect;
            $creatureUpdate->put('name', $creature->name);
            $creatureUpdate->put('body', $creature->body);
        } elseif ($post['type'] === 'visibility') {
            $creature->update(['visible' => $post['visible']]);
            $creatureUpdate->put('visible', $post['visible']);
            $creatureUpdate->put('hasMarker', !$creature->markerless);
            if (!$creature->markerless) {
                $creatureUpdate->put('marker', $creature->marker);
                $creatureUpdate->put('markerVisible', $creature->marker->visible);
            }
            $res = true;
        }
        broadcast(new CreatureUpdate($creatureUpdate))->toOthers();
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