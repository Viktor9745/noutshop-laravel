<?php

namespace App\Http\Controllers\Auth2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class RegisterController extends Controller
{
    public function create(){
        return view('auth.register');
    }

    public function register(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users',
            'password'=>'required|min:6|confirmed',
            'ava'=>'image|mimes:jpg, png, jpeg, gif, svg|max:2048',
        ]);

        // $input = $request->all();
        if($avatar = $request->file('ava')){
            $destinationPath='storage/userImages';
            $userImage=date('YmdHis') . "." . $avatar->getClientOriginalExtension();
            $avatar->move($destinationPath,$userImage);

            $user = User::create([
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'password'=> Hash::make($request->input('password')),
                'role_id'=>Role::where('name', 'user')->first()->id,
                'ava'=>$userImage,
            ]);

            Auth::login($user);

            return redirect()->route('items.index');
        }

        $user = User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=> Hash::make($request->input('password')),
            'role_id'=>Role::where('name', 'user')->first()->id,
        ]);

        Auth::login($user);

        return redirect()->route('items.index');
    }
}
