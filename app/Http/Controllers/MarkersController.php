<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Debug\Debug;
use App\Events\MarkerUpdate;
use App\Models\Map;
use App\Models\Marker;
use App\Models\Place;
use App\Models\Creature;
use Notify;
use Illuminate\Support\Facades\Auth;

class MarkersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Marker::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->post('name');
        $marker = new Marker;
        $marker->lat = $request->post('lat');
        $marker->lng = $request->post('lng');
        $marker->map_id = $request->post('map_id');
        if ($request->post('type') === 'place') {
            $marker->place_id = $request->post('id');
            $type = 'place';
        } elseif ($request->post('type') === 'creature') {
            $marker->creature_id = $request->post('id');
            $marker->icon = 'user';
            $marker->color = 'orange';
            $marker->selected_color = 'yellow';
            $marker->shape = 'penta';
            $marker->selected_shape = 'square';
            $type = 'creature';
        }
        $marker->save();
        $marker = Marker::where('id', $marker->id)->with(['place', 'creature'])->get()[0];
        $markerUpdate = collect([
            'map_id' => $request->post('map_id'),
            'id' => $marker->id,
            'update_type' => 'marker',
            'marker_type' => 'map',
            'marker' => $marker,
            'compendium_type' => $type
        ]);
        broadcast(new MarkerUpdate($markerUpdate))->toOthers();
        return compact('marker');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function show(Marker $marker)
    {
        $isDm = $marker->map->campaign->dm->id === Auth::user()->id;
        $onMap = Str::contains($_SERVER['HTTP_REFERER'], 'maps');

        if ($marker->place_id) {
            $place = Place::find($marker->place_id);
            $lastUpdated = $place->updated_at;
            $showComponent = view('components.show-place', compact('place', 'isDm', 'lastUpdated', 'onMap'))->render();
        } else if ($marker->creature_id) {
            $creature = Creature::find($marker->creature_id);
            $lastUpdated = $creature->updated_at;
            $showComponent = view('components.show-creature', compact('creature', 'isDm', 'lastUpdated', 'onMap'))->render();
        }

        return [
            'marker' => $marker,
            'showComponent' => $showComponent
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function edit(Marker $marker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marker $marker)
    {
        $markerUpdate = collect([
            'map_id' => $request['map_id'],
            'id' => $marker->id,
            'update_type' => $request['type'],
            'marker_type' => 'map'
        ]);
        switch ($request['type']) {
            case 'movement':
                Marker::where('id', $marker->id)->update([
                    'lat' => $request['lat'],
                    'lng' => $request['lng']
                ]);
                $markerUpdate->put('lat', $request['lat']);
                $markerUpdate->put('lng', $request['lng']);
                broadcast(new MarkerUpdate($markerUpdate))->toOthers();
                return true;
            case 'icon':
                Marker::where('id', $marker->id)->update([
                    'icon' => $request['icon']
                ]);
                $markerUpdate->put('icon', $request['icon']);
                broadcast(new MarkerUpdate($markerUpdate))->toOthers();
                return true;
            case 'visibility':
                $marker->update([
                    'visible' => $request['visible'] ? 1 : 0
                ]);
                $markerUpdate->put('visible', $request['visible']);
                broadcast(new MarkerUpdate($markerUpdate))->toOthers();
                return true;
            case 'lock':
                $marker->update([
                    'locked' => $request['locked'] ? 1 : 0
                ]);
                return true;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marker = Marker::find($id);
        $markerUpdate = collect([
            'map_id' => $marker->map->id,
            'id' => $marker->id,
            'update_type' => 'delete',
            'marker_type' => 'map',
            'marker' => $marker
        ]);

        if ($marker->place_id) {
            $markerUpdate->put('compendium_item_id', $marker->place->id);
            $markerUpdate->put('compendium_type', 'place');
        } else if ($marker->creature_id) {
            $markerUpdate->put('compendium_item_id', $marker->creature->id);
            $markerUpdate->put('compendium_type', 'creature');
        }
        $marker->delete();
        broadcast(new MarkerUpdate($markerUpdate))->toOthers();
        return ['status' => 200];
    }
}