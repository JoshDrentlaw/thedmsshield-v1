<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Debug\Debug;
use App\Models\Map;
use App\Models\Marker;
use App\Models\Place;
use Notify;

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
        if ($request->post('placeId')) {
            $place_id = $request->post('placeId');
            $place = Place::find($place_id);
        } else {
            $place = new Place;
            $place->campaign_id = $request->post('campaign_id');
            $place->url = Str::slug($name, '_');
            $place->name = $name;
            $place->body = "<p>Tell everyone about {$name}</p>";
            $place->save();
            $place_id = $place->id;
        }
        $marker->top = $request->post('top');
        $marker->left = $request->post('left');
        $marker->map_id = $request->post('map_id');
        $marker->place_id = $place_id;
        $marker->save();
        $marker = Marker::where('id', $marker->id)->with('place')->get()[0];
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
        return $marker;
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
        switch ($request['type']) {
            case 'movement':
                return Marker::where('id', $marker->id)->update([
                    'lat' => $request['lat'],
                    'lng' => $request['lng']
                ]);
            case 'note':
                return Place::where('id', $marker->id)->update([
                    'body' => $request['body'],
                    'name' => $request['name']
                ]);
            case 'icon':
                return Marker::where('id', $marker->id)->update([
                    'icon' => $request['icon']
                ]);
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
        $marker->delete();
        return ['status' => 200, 'message' => 'Marker deleted', 'class' => 'alert-success'];
    }
}