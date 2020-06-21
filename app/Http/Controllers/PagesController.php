<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {
        $title = 'Welcome to The DM\'s Shield';
        return view('pages.index')->with(['title' => $title]);
    }

    public function cypher_calculator() {
        return view('pages.cypher_calculator');
    }
}
