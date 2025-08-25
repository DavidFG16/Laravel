<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    function login(Request $request){
        return response()->json(['message' => 'Hello Campers'], 200);
    }
}
