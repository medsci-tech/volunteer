<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function error (Request $request)
    {
        $message = '';
        if($request){
            $message = $request->message;
        }
        return view('home.error')->with(['message' => $message]);
    }

    public function unavailable ()
    {
        return view('home.unavailable');
    }

//    public function error ()
//    {
//        return view('home.error');
//    }

} /*class*/
