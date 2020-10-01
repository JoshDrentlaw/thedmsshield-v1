<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Map;
use App\Marker;
use Cloudinary\Uploader;
use JD\Cloudder\Facades\Cloudder;
use App\Debug;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

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
        $map->campaign_id = $request->post('map-id');
        $map->map_name = $request->post('map-name');
        $map->map_url = implode('_', explode(' ', strtolower($map->map_name)));
        $image = $request->file('map-image')->path();
        Cloudder::upload($image, 'thedmsshield.com/maps/'.$map->map_url);
        $map->map_public_id = Cloudder::getPublicId();
        list($width, $height) = getimagesize($image);
        $map->map_width = $width;
        $map->map_height = $height;
        $map->map_image_url = Cloudder::secureShow($map->map_public_id, ['width' => $width, 'height' => $height, 'format' => 'jpg']);
        $map->map_preview_url = Cloudder::secureShow($map->map_public_id, ['width' => 300, 'height' => 195, 'crop' => 'scale', 'format' => 'jpg']);
        $map->save();
        $html = view('components.map-list', compact('map'))->render();
        return  ['status' => 200, 'message' => 'Map added', 'map' => $map, 'html' => $html];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($campaign_id, $map_id)
    {
        Debug::log('Hit controller');
        if (!Auth::check())  return redirect('/');
        Debug::log('Authorized');

        $map = Map::firstWhere('map_url', $map_id);
        Debug::log('got map');
        $campaign = $map->campaign;
        Debug::log('got campaign');

        if ($campaign_id !== $campaign->url) return redirect('/');
        Debug::log('correct url');
        if (Gate::denies('campaign-member', $campaign)) return redirect('/');
        Debug::log('Part of campaign');

        $markers = [];
        foreach ($map->markers as $marker) {
            $markers[] = $marker;
        }
        $user = Auth::user();

        Debug::log('lets do this thing');
        return view('maps.show', [
            'map' => $map,
            'campaign' => $map->campaign,
            'dm' => $map->campaign->dm,
            'isDm' => $user->id === $map->campaign->dm->id,
            'markers' => $markers,
            'players' => $map->active_players
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
    public function update(Request $request, $id, $type)
    {
        switch ($type) {
            case 'image':
                if ($request->hasFile('new-map-image')) {
                    $this->validate($request,[
                        'new-map-image' => 'required|mimes:jpeg,jpg,png|between:1, 6000'
                    ]);
                    $map = Map::find($id);
                    Cloudder::destroyImages([$map->map_public_id]);
                    $filename = $request->file('new-map-image')->path();
                    Cloudder::upload($filename, $map->map_public_id);
                    list($map_width, $map_height) = getimagesize($filename);
                    $map_image_url = Cloudder::secureShow($map->map_public_id, ['width' => $map_width, 'height' => $map_height, 'format' => 'jpg']);
                    $map_preview_url = Cloudder::secureShow($map->map_public_id, ['width' => 300, 'height' => 195, 'crop' => 'scale', 'format' => 'jpg']);
                    Map::where('id', $id)->update(compact('map_image_url', 'map_preview_url', 'map_width', 'map_height'));
                    return ['status' => 200, 'map' => $map, 'message' => 'Map image updated'];
                } else {
                    return ['status' => 500, 'request' => $request];
                }
            case 'name':
                $map_url = implode('_', explode(' ', strtolower($request->map_name)));
                Map::where('id', $id)->update(['map_name' => $request->map_name, 'map_url' => $map_url]);
                return ['status' => 200, 'message' => 'Map name updated', 'map_url' => $map_url];
        }
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
