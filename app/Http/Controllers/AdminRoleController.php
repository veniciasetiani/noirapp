<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\permission;
use Illuminate\Http\Request;
use App\Models\AvailableTime;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Str;


class AdminRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.role.index', [
            'title' => "Request Role",
            'active' => 'request role',
            'permissions' => permission::all()->where('statcode','REQ') // Use the correct model name
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
        $data = permission::find($request->id);
        $validated['role_id'] = $data->role_id;
        $validated['category_id'] = $data->category_id;
        $validated['price'] = $data->price;
        $validated['norekening'] = $data->norekening;
        $validated['body'] = $data->body;
        $validated['excerpt'] = str::limit($data->body, 100);

        $validatedpermission['statcode'] = "APV";


        User::where('id',$data->user_id)
        ->update($validated);

        permission::where('id',$data->id)
        ->update($validatedpermission);

        return redirect('/dashboard/role')->with('success','User Role Request Has Been Approved!');

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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = permission::findOrFail($id);
        $validatedpermission['statcode'] = "RJC";

        
        permission::where('id',$id)
        ->update($validatedpermission);

        AvailableTime::where('user_id', $permission->user_id)->delete();

        return redirect('/dashboard')->with('success', 'Role request deleted successfully');
    }
}
