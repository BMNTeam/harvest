<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function postSignUp(Request $request)
    {
        $this->validate( $request, [
            'email'         => 'required|email|unique:users',
            'name'          => 'required|max:120',
            'password'      => 'required|min:4'
        ]);

        $email          = $request['email'];
        $name     = $request['name'];
        $password       = bcrypt($request['password']);

        $user = new User();
        $user->email                = $email;
        $user->name                 = $name;
        $user->password             = $password;

        $user->save();

        Auth::login($user);

        return redirect()->route('users');
    }

    public function postSignIn(Request $request)
    {
        $this->validate( $request, [
            'name'         => 'required',
            'password'      => 'required'
        ]);

        if( Auth::attempt([
                'name' => $request['name'],
                'password' => $request['password']
            ], true
        ) ) {
            return redirect( route( 'users') );
        }
        return redirect()->back();
    }

    public function getLogout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
