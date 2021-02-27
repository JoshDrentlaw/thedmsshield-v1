<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Debug\Debug;
use App\Events\MarkerUpdate;
use App\Models\Map;
use App\Models\Marker;
use App\Models\Place;
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
        // auth()->user()->id;
        return Marker::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
        $marker->place_id = $request->post('placeId');
        $marker->save();
        $marker = Marker::where('id', $marker->id)->with('place')->get()[0];
        $markerUpdate = collect([
            'map_id' => $request->post('map_id'),
            'id' => $marker->id,
            'update_type' => 'marker',
            'marker_type' => 'map',
            'marker' => $marker
        ]);
        broadcast(new MarkerUpdate($markerUpdate))->toOthers();
        return compact('marker');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function show(Marker $marker)
    {
        $place = Place::find($marker->place_id);
        $lastUpdated = $place->updated_at;
        $isDm = $marker->map->campaign->dm->id === Auth::user()->id;
        $onMap = Str::contains($_SERVER['HTTP_REFERER'], 'maps');
        $showComponent = view('components.show-place', compact('place', 'isDm', 'lastUpdated', 'onMap'))->render();
        return [
            'marker' => $marker,
            'showComponent' => $showComponent
        ];
        // return Marker::find($marker);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Marker  $marker
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
     * @param  \App\Marker  $marker
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
            case 'note':
                Place::where('id', $marker->id)->update([
                    'body' => $request['body'],
                    'name' => $request['name']
                ]);
                $markerUpdate->put('body', $request['body']);
                $markerUpdate->put('name', $request['name']);
                // broadcast(new MarkerUpdate($markerUpdate))->toOthers();
                return true;
            case 'icon':
                Marker::where('id', $marker->id)->update([
                    'icon' => $request['icon']
                ]);
                $markerUpdate->put('icon', $request['icon']);
                broadcast(new MarkerUpdate($markerUpdate))->toOthers();
                return true;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Marker  $marker
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
            'marker' => $marker,
            'placeId' => $marker->place->id
        ]);
        $marker->delete();
        broadcast(new MarkerUpdate($markerUpdate))->toOthers();
        return ['status' => 200];
    }
}