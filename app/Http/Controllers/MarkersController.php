<?php

namespace App\Http\Controllers;

use App\Debug;
use App\Map;
use App\Marker;
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
        $marker->note_title = 'New marker';
        $marker->note_body = '<p>New note</p>';
        $marker->top = $request->post('top');
        $marker->left = $request->post('left');
        $marker->map_id = $request->post('map_id');
        $marker->save();
        return $marker;
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
                return Marker::where('id', $marker->id)->update([
                    'note_body' => $request['note_body'],
                    'note_title' => $request['note_title']
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marker $marker)
    {
        //
    }
}
