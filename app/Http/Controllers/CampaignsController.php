<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Models\Debug;
use Illuminate\Support\Facades\Gate;

class CampaignsController extends Controller
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
    public function index()
    {
        //
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
            'campaign-image'=>'mimes:jpeg,bmp,jpg,png|between:1, 6000',
            'name' => 'required',
            'description' => 'required'
        ]);
        $env = env('APP_ENV');
        $user = Auth::user();
        $campaign = new Campaign;
        $campaign->dm_id = $user->id;
        $username = $user->username;
        $campaign->name = $validated['name'];
        $campaign->description = $validated['description'];
        $campaign->url = implode('_', explode(' ', strtolower($campaign->name)));
        if (isset($validated['campaign-image'])) {
            $image = $request->file('campaign-image')->path();
            Cloudder::upload($image, "thedmsshield.com/{$env}/users/{$username}/campaigns/" . $campaign->url . '/cover');
            $campaign->cover_public_id = Cloudder::getPublicId();
        }
        $campaign->save();
        $html = view('components.campaign-list', compact('campaign'))->render();
        return  ['status' => 200, 'message' => 'Campaign added', 'campaign' => $campaign, 'html' => $html];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $campaign = Campaign::firstWhere('url', $id);
        $dm = $campaign->dm;
        $maps = $campaign->maps;
        $players = $campaign->active_players;
        $user = Auth::user();
        if (Gate::denies('campaign-member', $campaign)) {
            return redirect('/');
        }

        return view('campaigns.show', [
            'dm' => $dm,
            'campaign' => $campaign,
            'maps' => $maps,
            'players' => $players,
            'isDm' => $user->id === $dm->id
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
                if ($request->hasFile('new-campaign-image')) {
                    $this->validate($request,[
                        'new-campaign-image' => 'required|mimes:jpeg,jpg,png|between:1, 6000'
                    ]);
                    $env = env('APP_ENV');
                    $user = Auth::user();
                    $username = $user->username;
                    $campaign = Campaign::find($id);
                    $filename = $request->file('new-campaign-image')->path();
                    Cloudder::upload($filename, "thedmsshield.com/{$env}/users/{$username}/campaigns/" . $campaign->url . '/cover');
                    $cover_public_id = Cloudder::getPublicId();
                    Campaign::where('id', $id)->update(compact('cover_public_id'));
                    return ['status' => 200, 'campaign' => $campaign, 'message' => 'Campaign image updated'];
                } else {
                    return ['status' => 500, 'request' => $request];
                }
            case 'name':
                $url = implode('_', explode(' ', strtolower($request->name)));
                Campaign::where('id', $id)->update(['name' => $request->name, 'url' => $url]);
                return ['status' => 200, 'message' => 'Campaign name updated', 'url' => $url];
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
        $campaign = Campaign::find($id);
        Cloudder::destroyImages([$campaign->campaign_public_id]);
        $campaign->delete();
        return ['status' => 200, 'message' => 'Campaign deleted'];
    }

    public function get_active_players($id)
    {
        $campaign = Campaign::find($id);
        return $campaign->players->reject(function($player) {
            return $player->pivot->accept == 1;
        });
    }
}
