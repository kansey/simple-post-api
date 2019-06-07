<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ShowProfile extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        dd(User::findOrFail($request->id));
        return view('user.profile', ['user' => User::findOrFail($request->id)]);
    }
}
