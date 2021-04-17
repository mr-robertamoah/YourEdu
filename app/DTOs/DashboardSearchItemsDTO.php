<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class DashboardSearchItemsDTO
{
    

    public static function createFromRequest(Request $request)
    {
        $self = static;

        return $self;
    }
}
