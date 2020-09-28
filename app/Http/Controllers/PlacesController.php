<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Campaign;
use App\Place;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Debug;
use Exception;
use Illuminate\Support\Facades\Gate;

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
        //
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

        $last_updated = $place->updated_at;

        $dm = $campaign->dm;
        $user = Auth::user();

        return view('places.show', [
            'place' => $place,
            'dm' => $dm,
            'campaign' => $campaign,
            'isDm' => $user->id === $dm->id,
            'uri' => $_SERVER['REQUEST_URI'],
            'last_updated' => $last_updated
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
        try {
            $res = ['status' => 200];
            $post = $request->post();
            if (isset($post['body'])) {
                $valid = $request->validate([
                    'body' => 'max:2000'
                ]);
                $updated = Place::where('id', $id)->first()->updated_at;
                $res['updated_at'] = $updated->format('m-d-Y h:i:s a');
                Place::where('id', $id)->update(['body' => $valid['body']]);
            }
            if (isset($post['name'])) {
                $valid = $request->validate([
                    'name' => 'max:50'
                ]);
                $url = strtolower(str_replace(' ', '_', $valid['name']));
                $http = explode('/', $_SERVER['HTTP_REFERER']);
                array_splice($http, -1, 1, $url);
                $res['redirect'] = implode('/', $http);
                Place::where('id', $id)->update(['name' => $valid['name'], 'url' => $url]);
            }
            return $res;
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
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
        //
    }
}
