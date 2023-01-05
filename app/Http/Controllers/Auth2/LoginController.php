<?php

namespace App\Http\Controllers\Auth2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function create(){
        return view('auth.login');
    }
    public function login(Request $request){
        if(Auth::check()){
            return redirect()->intended('/items');
        }

        $validated = $request->validate([
            'email'=>'required|email',
            'password'=>'required|string',
        ]);

        if(Auth::attempt($validated)){
            if(Auth::user()->role->name=="admin"){
                return redirect()->intended('/adm/users');
            }else if(Auth::user()->role->name=="moderator"){
                return redirect()->intended('/adm/categories');
            }
            return redirect()->intended('/items');
        }
        return back()->withErrors(__('messages.incor_pass_email'));
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login.form');
    }
}
