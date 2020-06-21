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
        $maps = $dm->map;
        return view('pages.dashboard', compact('maps', 'user', 'dm'));
    }
}
