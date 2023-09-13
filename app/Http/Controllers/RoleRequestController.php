<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\category;
use App\Models\permission;
use App\Models\role;

use Illuminate\Http\Request;

class RoleRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('requestrole.index',
        [
            "title" => "request role",
            'active' => 'request role',
            'categories' => category::all(),
            'roles' => role::whereNotIn('name',['user','admin'])->get()

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' =>'required',
            'price' => 'required',
            'image' => 'image|file|max:1024',
            'category_id' => 'required'
        ]);

        if($request->file('image')){
            $validated['image'] = $request->file('image')->store('role-request-images');
        }
        $validated['user_id'] = auth()->user()->id;
        $validated['statcode'] = "REQ";


        permission::create($validated);
        return redirect('/role/request')->with('success','Request Has Been Submitted!');    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}