<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface ItemDataContract
{
    
    public static function createFromRequest(Request $request);
}