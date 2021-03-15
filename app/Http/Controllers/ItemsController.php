<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\Item;
use App\Models\CompendiumItem;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Debug\Debug;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

use App\Broadcasting\CompendiumChannel;
use App\Events\ItemUpdate;

class ItemsController extends Controller
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
    public function index($campaign_url)
    {
        if (!$campaign_url) {
            Redirect::to($_SERVER['HTTP_REFERER']);
        }
        $campaign = Campaign::firstWhere('url', $campaign_url);
        $items = Item::all()->where('campaign_id', $campaign->id);
        return view('items.index', compact('campaign', 'items'));
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
        return view('items.create', compact('map'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = CompendiumItem::storeCompendiumItem(new Item, $request);
        $itemUpdate = collect([
            'campaign_id' => $item->campaign_id,
            'id' => $item->id,
            'type' => 'newItem',
            'item' => $item
        ]);
        broadcast(new ItemUpdate($itemUpdate))->toOthers();
        return ['status' => 200, 'item' => $item];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($campaign_id, $item_id)
    {
        if (!Auth::check()) return redirect('/');

        $item = Item::firstWhere('url', $item_id);
        $campaign = $item->campaign;

        if ($campaign_id !== $campaign->url) return redirect('/');
        if (Gate::denies('campaign-member', $campaign)) return redirect('/');

        $lastUpdated = $item->updated_at;

        $dm = $campaign->dm;
        $user = Auth::user();

        return view('items.show', [
            'item' => $item,
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
        return [
            'status' => 200,
            'showComponent' => CompendiumItem::showComponent(Item::find($id), 'item', 'isDm')
        ];
    }

    public function show_to_players($id)
    {
        $item = Item::find($id);
        $itemUpdate = collect([
            'campaign_id' => $item->campaign_id,
            'id' => $item->id,
            'type' => 'showToPlayers',
            'markerless' => $item->markerless,
            'markerId' => !$item->markerless ? $item->marker->id : false
        ]);
        broadcast(new ItemUpdate($itemUpdate))->toOthers();
        return ['status' => 200];
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
     * @param  App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $res = ['status' => 200];
        $post = $request->post();
        $itemUpdate = collect([
            'campaign_id' => $item->campaign_id,
            'id' => $item->id,
            'type' => $post['type']
        ]);
        if ($post['type'] === 'edit') {
            $compendiumItem = new CompendiumItem();
            $item = $compendiumItem::updateCompendiumItem($request, $item);
            $res['updated_at'] = $item->updated_at;
            $res['redirect'] = $compendiumItem::$redirect;
            $itemUpdate->put('name', $item->name);
            $itemUpdate->put('body', $item->body);
        } elseif ($post['type'] === 'visibility') {
            $item->update(['visible' => $post['visible']]);
            $itemUpdate->put('visible', $post['visible']);
            $itemUpdate->put('hasMarker', !$item->markerless);
            if (!$item->markerless) {
                $itemUpdate->put('marker', $item->marker);
                $itemUpdate->put('markerVisible', $item->marker->visible);
            }
            $res = true;
        }
        broadcast(new ItemUpdate($itemUpdate))->toOthers();
        return $res;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        $itemUpdate = collect([
            'campaign_id' => $item->campaign_id,
            'id' => $item->id,
            'item' => $item,
            'type' => 'delete',
            'item_type' => 'item'
        ]);
        $item->delete();
        broadcast(new ItemUpdate($itemUpdate))->toOthers();
        return ['status' => 200];
    }
}