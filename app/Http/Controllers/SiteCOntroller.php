<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class SiteCOntroller extends Controller
{
    public function index() {
        if(Auth::check() && Auth::user()->role == 1){
            $user_count = User::count();
            return view('admin.home', compact('user_count'));
        } 
        else{
            Auth::logout();
            return view('admin.loginform');
        }
    }
}
