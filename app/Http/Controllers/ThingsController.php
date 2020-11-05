<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Campaign;
use App\Thing;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Debug;
use Exception;
use Illuminate\Support\Facades\Gate;

class ThingsController extends Controller
{
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
        $map = false;
        if (isset($_GET['map'])) {
            $map = true;
        }
        return view('things.create', compact('map'));
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
            'name' => 'required|unique:things,name|max:50',
            'body' => 'max:2000'
        ]);
        try {
            $thing = new Thing;
            $thing->url = Str::slug($validated['name'], '_');
            $thing->name = $validated['name'];
            $thing->body = $validated['body'];
            $thing->campaign_id = $request->post('campaign_id');
            $thing->save();
            return ['status' => 200, 'thing' => $thing];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($campaign_id, $thing_id)
    {
        if (!Auth::check()) return redirect('/');

        $thing = Thing::firstWhere('url', $thing_id);
        $campaign = $thing->campaign;

        if ($campaign_id !== $campaign->url) return redirect('/');
        if (Gate::denies('campaign-member', $campaign)) return redirect('/');

        $lastUpdated = $thing->updated_at;

        $dm = $campaign->dm;
        $user = Auth::user();

        return view('things.show', [
            'thing' => $thing,
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
        $thing = Thing::find($id);
        $lastUpdated = $thing->updated_at;
        $showComponent = view('components.show-thing', compact('thing', 'isDm', 'lastUpdated'))->render();
        return ['status' => 200, 'showComponent' => $showComponent];
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
                $updated = Thing::where('id', $id)->first()->updated_at;
                $res['updated_at'] = $updated;
                Thing::where('id', $id)->update(['body' => $valid['body']]);
            }
            if (isset($post['name'])) {
                $valid = $request->validate([
                    'name' => 'max:50'
                ]);
                $url = strtolower(str_replace(' ', '_', $valid['name']));
                $http = explode('/', $_SERVER['HTTP_REFERER']);
                array_splice($http, -1, 1, $url);
                $res['redirect'] = implode('/', $http);
                Thing::where('id', $id)->update(['name' => $valid['name'], 'url' => $url]);
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
