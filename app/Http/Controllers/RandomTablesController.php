<?php

namespace App\Http\Controllers;

use App\Models\RandomTable;
use App\Models\Campaign;

use Illuminate\Http\Request;
use App\Debug\Debug;

class RandomTablesController extends Controller
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
        /* $request->validate([
            'name' => 'unique:random_tables,name'
        ]); */

        $randomTable = new RandomTable([
            'name' => $request->post('name'),
            'table_data' => $request->post('data'),
            'campaign_id' => $request->post('campaignId')
        ]);
        $randomTable->save();
        $campaign = Campaign::find($request->post('campaignId'));
        $tables = $campaign->random_tables->toArray();
        return ['random_tables' => $tables];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $table = RandomTable::find($id);

        return $table;
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
        $table = RandomTable::find($id);
        return $table->update($request->post());
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