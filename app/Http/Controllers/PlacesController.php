<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\Place;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Debug\Debug;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

use App\Broadcasting\CompendiumChannel;
use App\Events\PlaceUpdate;

class PlacesController extends Controller
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
        $places = Place::all()->where('campaign_id', $campaign->id);
        return view('places.index', compact('campaign', 'places'));
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
        return view('places.create', compact('map'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:50',
            'body' => 'max:2000'
        ]);
        try {
            $place = new Place;
            $place->url = Str::slug($validated['name'], '_');
            $place->name = $validated['name'];
            $place->body = $validated['body'];
            $place->campaign_id = $request->post('campaign_id');
            $place->save();
            $placeUpdate = collect([
                'campaign_id' => $place->campaign_id,
                'id' => $place->id,
                'type' => 'newPlace',
                'place' => $place
            ]);
            broadcast(new PlaceUpdate($placeUpdate))->toOthers();
            return ['status' => 200, 'place' => $place];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($campaign_id, $place_id)
    {
        if (!Auth::check()) return redirect('/');

        $place = Place::firstWhere('url', $place_id);
        $campaign = $place->campaign;

        if ($campaign_id !== $campaign->url) return redirect('/');
        if (Gate::denies('campaign-member', $campaign)) return redirect('/');

        $lastUpdated = $place->updated_at;

        $dm = $campaign->dm;
        $user = Auth::user();

        return view('places.show', [
            'place' => $place,
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
        $place = Place::find($id);
        $lastUpdated = $place->updated_at;
        $onMap = Str::contains($_SERVER['HTTP_REFERER'], 'maps');
        $showComponent = view('components.show-place', compact('place', 'isDm', 'lastUpdated', 'onMap'))->render();
        return ['status' => 200, 'showComponent' => $showComponent];
    }

    public function show_to_players($id)
    {
        $place = Place::find($id);
        $placeUpdate = collect([
            'campaign_id' => $place->campaign_id,
            'id' => $place->id,
            'type' => 'showToPlayers',
            'markerless' => $place->markerless,
            'markerId' => !$place->markerless ? $place->marker->id : false
        ]);
        broadcast(new PlaceUpdate($placeUpdate))->toOthers();
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
     * @param  App\Models\Place $place
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        $res = ['status' => 200];
        $post = $request->post();
        if (isset($post['body'])) {
            $valid = $request->validate([
                'body' => 'max:65535'
            ]);
            $updated = $place->first()->updated_at;
            $res['updated_at'] = $updated;
            $place->update(['body' => $valid['body']]);
        }
        if (isset($post['name'])) {
            $valid = $request->validate([
                'name' => 'max:50'
            ]);
            $valid['name'] = trim($valid['name']);
            $url = Str::slug($valid['name'], '_');
            if ($url !== $place->url) {
                $http = explode('/', $_SERVER['HTTP_REFERER']);
                array_splice($http, -1, 1, $url);
                $res['redirect'] = implode('/', $http);
            }
            $place->update(['name' => $valid['name'], 'url' => $url]);
        }
        $place->refresh();
        $placeUpdate = collect([
            'campaign_id' => $place->campaign_id,
            'id' => $place->id,
            'type' => 'edit',
            'name' => $place->name,
            'body' => $place->body
        ]);
        broadcast(new PlaceUpdate($placeUpdate))->toOthers();
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