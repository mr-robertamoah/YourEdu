<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class YourEduController extends Controller
{
    public function index ()
    {
        return view('youredu');
    }
}
