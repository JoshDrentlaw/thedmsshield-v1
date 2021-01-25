<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MapChatMessage;
use App\Models\Debug;
use App\Events\NewMapChatMessage;
use App\Jobs\ProcessMapChat;
use Illuminate\Support\Facades\Auth;

class MapChatMessagesController extends Controller
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
        $validated = $request->validate([
            'message' => 'max:3000|required'
        ]);
        $mapChatMessage = new MapChatMessage([
            'message' => $validated['message'],
            'map_id' => $request->post('mapId'),
            'user_id' => $request->post('userId')
        ]);
        $mapChatMessage->save();

        broadcast(new NewMapChatMessage($mapChatMessage))->toOthers();
        return compact('mapChatMessage');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}