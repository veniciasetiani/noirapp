<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function index(){
        return view('register.index', [
            "title" => "register",
            'active' => 'register'
            // Post::find($id)

        ]);
    }

    public function register(Request $request){

        $validated = $request -> validate([
            'username' => 'required|min:3|max:255|unique:users',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:5',
            'name' =>'required|max:255',
            'idcardnumber' =>'required|numeric|digits:16|unique:users'
        ]);

        $validated["password"] = bcrypt($validated['password']);
        $validated["role_id"] = 4;
        $validated["idcardstatcode"] = "REQ";

        User::create($validated);

        $request -> session() ->flash('success','Registration Successful , Please Login!');
        return redirect('/login');
    }
}
