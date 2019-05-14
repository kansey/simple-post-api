<?php

namespace App\Services;

use Illuminate\Http\Request;

class PostService
{
    public function create(Request $request)
    {
        return $request->all();
    }
}
