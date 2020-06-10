<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Map;
use App\Marker;
use Cloudinary\Uploader;
use JD\Cloudder\Facades\Cloudder;
use App\Debug;

class MapsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maps = Map::all();
        return view('maps.index', compact('maps'));
    }

    public function get_all_markers()
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
        $this->validate($request,[
            'map-image'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 6000',
        ]);
        $map = new Map;
        $map->user_id = $request->post('map-id');
        $map->map_name = $request->post('map-name');
        $map->map_url = implode('_', explode(' ', strtolower($map->map_name)));
        $image = $request->file('map-image');
        Cloudder::upload($image, 'thedmsshield.com/'.$map->map_url);
        $map->map_public_id = Cloudder::getPublicId();
        list($width, $height) = getimagesize($image);
        $map->map_width = $width;
        $map->map_height = $height;
        $map->map_image_url = Cloudder::secureShow($map->map_public_id);
        $map->map_preview_url = Cloudder::secureShow($map->map_public_id, ['width' => 300, 'height' => 195, 'crop' => 'scale']);
        $map->save();
        return  ['status' => 200, 'message' => 'Map added', 'map' => $map];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $map = Map::find($id);
        $markers = $map->markers;
        return view('maps.show', [
            'map' => $map,
            'markers' => $markers
        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $map = Map::find($id);
        Cloudder::destroyImages([$map->map_public_id]);
        $map->delete();
        return ['status' => 200, 'message' => 'Map deleted'];
    }
}
