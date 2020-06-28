<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\DM;
use App\Debug;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $dm = $user->dm;
        $maps = $dm->maps;
        return view('pages.dashboard', compact('maps', 'user', 'dm'));
    }

    public function update(Request $request, $id, $type) {
        switch ($type) {
            case 'description':
                $status = User::where('id', $id)->update(['description' => $request->post('description')]) ? 200 : 500;
                return ['status' => $status, 'message' => 'Description saved.'];
            default:
                return false;
        }
    }

    public function player_search(Request $request) {
        $search = $request->post('search');
        $users = User::select('id', 'name as text', 'description')->where('id', 'like', "%$search%")->orWhere('name', 'like', "%$search%")->get();
        echo json_encode(['results' => $users]);
        exit;
    }
}
