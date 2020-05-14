<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {
        $title = 'Welcome to The DMs Shield';
        return view('pages.index')->with(['title' => $title]);
    }

    public function cypher_roller() {
        return view('pages.cypher_roller');
    }
}
