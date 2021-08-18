<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function index()
    {
        return view('urls.index');
    }

    public function store()
    {
        return;
    }

    public function show()
    {
        return view('urls.show');
    }
}
