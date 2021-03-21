<?php

namespace App\Http\Controllers;

class ActivityController extends Controller
{
    /**
     * Show the list of Activity search results.
     */
    public function list()
    {
        return view('article.list', []);
    }
}
