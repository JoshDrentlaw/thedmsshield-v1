<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\Map;
use App\Models\Marker;
use Cloudinary\Uploader;
use JD\Cloudder\Facades\Cloudder;
use App\Models\Debug;
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
        $validated = $this->validate($request,[
            'map-image'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 6000',
            'map-name' => 'required|unique:maps,name'
        ]);
        $map = new Map;
        $map->campaign_id = $request->post('campaign-id');
        $map->name = $validated['map-name'];
        $map->url = Str::slug($validated['map-name'], '_');
        $image = $validated['map-image']->path();
        $env = env('APP_ENV');
        $username = Auth::user()->username;
        $campaign = Campaign::find($request->post('campaign-id'))->url;
        Cloudder::upload($image, "thedmsshield.com/{$env}/users/{$username}/campaigns/{$campaign}/maps/" . $campaign->url . '_' . $map->url);
        $map->public_id = Cloudder::getPublicId();
        list($width, $height) = getimagesize($image);
        $map->width = $width;
        $map->height = $height;
        $map->save();
        $isDm = true;
        $html = view('components.map-list', compact('map', 'isDm'))->render();
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
        if (!Auth::check())  return redirect('/');

        $map = Map::firstWhere('url', $map_id);
        $campaign = $map->campaign;

        if ($campaign_id !== $campaign->url) return redirect('/');
        if (Gate::denies('campaign-member', $campaign)) return redirect('/');

        /* $markers = [];
        foreach ($map->markers as $marker) {
            $markers[] = $marker;
        } */
        $user = Auth::user();

        return view('maps.show', [
            'map' => $map,
            'map_url' => env('CLOUDINARY_IMG_PATH') . 'v' . time() . '/' . $map->public_id . '.jpg',
            'campaign' => $map->campaign,
            'dm' => $map->campaign->dm,
            'isDm' => $user->id === $map->campaign->dm->id,
            'markers' => $map->markers,
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
                    Map::where('id', $id)->update(compact('map_width', 'map_height'));
                    return ['status' => 200, 'map' => $map, 'message' => 'Map image updated'];
                } else {
                    return ['status' => 500, 'request' => $request];
                }
            case 'name':
                $validated = $this->validate($request,[
                    'map-name' => 'required|unique:maps,name'
                ]);
                $map_url = Str::slug($validated['map-name'], '_');
                $map = Map::find($id);
                $env = env('APP_ENV');
                $username = Auth::user()->username;
                $campaign = Campaign::find($request->post('campaign-id'))->url;
                $public_id = "thedmsshield.com/{$env}/users/{$username}/campaigns/{$campaign}/maps/" . $campaign->url . '_' . $map->url;
                Cloudder::rename($map->public_id, $public_id);
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
