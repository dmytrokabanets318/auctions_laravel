<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller{

    public function register(Request $request){

        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $error = $validator->errors()->first();
        }

        if(count(User::where('email', $request->email)->get()) > 0){
            return ["message" => "Email already registered, please login", "code" => 400];
        }


        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random();

        $user->save();

        return ["message" => "User created, please login", "code" => 200];

    }

    public function login(Request $request){    

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials, $request->remember)){
            return ["token" => Auth::user()->remember_token, "email" => Auth::user()->email];
        }else{
            return ["message" => "Invalid credentials", "code" => 401];
        }

    }

    public function logout(){

        Auth::logout();

        return ["message" => "User logged out"];

    }

    public function forgotPassword(Request $request){

        $validator = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->get();

        if(count($user) > 0){
            //Send email to reset password
            return ["message" => "An email has been sent to your inbox", "code" => 200];
        }else{
            return ["message" => "The email entered is invalid, please enter a correct email", "code" => 400];
        }

    }


}
