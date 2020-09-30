<?php

namespace App\Http\Controllers;

use App\Debug;
use App\Map;
use App\Marker;
use App\Place;
use Illuminate\Http\Request;
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
        $marker = new Marker;
        $place = new Place;
        list($name, $url) = self::getRandomWords();
        $place->campaign_id = $request->post('campaign_id');
        $place->url = $url;
        $place->name = $name;
        $place->body = '<p>New note</p>';
        $place->save();
        $marker->top = $request->post('top');
        $marker->left = $request->post('left');
        $marker->map_id = $request->post('map_id');
        $marker->place_id = $place->id;
        $marker->save();
        return compact('marker', 'place');
    }

    public static function getRandomWords()
    {
        $curl = curl_init();
        $url = sprintf("%s?%s", "https://random-word-api.herokuapp.com/word", http_build_query(['number' => 2, 'swear' => 0]));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = json_decode(curl_exec($curl));
        $name = ucwords(join(' ', $result));
        $url = join('_', $result);
        curl_close($curl);
        return [$name, $url];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function show(Marker $marker)
    {
        return Marker::find($marker);
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
                    'top' => $request['top'],
                    'left' => $request['left']
                ]);
            case 'note':
                return Place::where('id', $marker->id)->update([
                    'body' => $request['body'],
                    'name' => $request['name']
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
        $place_id = $marker->place_id;
        $place = Place::find($place_id);
        $marker->delete();
        $place->delete();
        return ['status' => 200, 'message' => 'Marker deleted', 'class' => 'alert-success'];
    }
}
