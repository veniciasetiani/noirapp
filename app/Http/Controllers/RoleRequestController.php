<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\User;
use App\Models\category;
use App\Models\permission;

use Illuminate\Http\Request;
use App\Models\AvailableTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user(); // Assuming you're using Laravel's authentication

        // Permissions data
        $permissions = DB::table('permissions')
            ->where('user_id', $user->id)
            ->where('statcode', 'APV')
            ->orderBy('created_at','desc')
            ->get(['id', 'category_id', 'role_id', 'user_id', 'price', 'imageprofile', 'image', 'video', 'norekening', 'statcode', 'body']);
        $permissions2 =  permission::orderBy('created_at','desc')->where('user_id', $user->id)->first();
        // Available times data
        $availableTimes = DB::table('available_times')
            ->where('user_id', $user->id)
            ->get(['id', 'user_id', 'day', 'start_time', 'end_time']);

        $status = '';
        if($permissions2) {
            if($permissions2->statcode == 'REQ'){
                $status = 'Waiting to be accepted by administrator';
            }
            elseif($permissions2->statcode == 'APV'){
                $status = 'Your previous role request has been accepted by administrator';
            }
            elseif($permissions2->statcode == 'RJC'){
                $status = 'Your previous role request has been rejected by administrator';
            }
        }
        return view('requestrole.index', [
            "title" => "request role",
            'active' => 'request role',
            'categories' => category::all(),
            'roles' => role::whereNotIn('name', ['user', 'admin'])->get(),
            'permissions' => $permissions,
            'availableTimes' => $availableTimes,
            'status' => $status
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
        $user = auth()->user();
        $lastdatastat = permission::where('user_id', $user->id)->orderBy('created_at','desc')->first();
        $dataREQ = permission::where('user_id', $user->id)->where('statcode','REQ')->first();

        // Validate the request
        $validated = $request->validate([
            'role_id' => 'required',
            'price' => ($request->role_id == 1) ? 'required|numeric|min:300|max:500' : 'required|numeric',
            'imageprofile' => isset($request->imageprofile) ? 'required|image|file|max:1024' : 'nullable|image|file|max:1024',
            'image' => isset($request->image) ? 'required|image|file|max:1024' : 'nullable|image|file|max:1024',
            'video' => isset($request->video) ? 'required|mimes:mp4,avi,wmv|max:10240' : 'nullable|mimes:mp4,avi,wmv|max:10240',
            'category_id' => 'required',
            'norekening' => 'required|max:16|min:16',
            'body' => 'required|max:255',
        ]);

        if ($request->hasFile('imageprofile')) {
            $validated['imageprofile'] = $request->file('imageprofile')->store('role-profile-images');
        } elseif (!$request->hasFile('imageprofile') && isset($lastdatastat) && $lastdatastat->imageprofile) {
            $validated['imageprofile'] = $lastdatastat->imageprofile;
        }


        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('role-request-images');
        } elseif (!$request->hasFile('image') && isset($lastdatastat) && $lastdatastat->image) {
            $validated['image'] = $lastdatastat->image;
        }


        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('role-request-videos');
        } elseif (!$request->hasFile('video') && isset($lastdatastat) && $lastdatastat->video) {
            $validated['video'] = $lastdatastat->video;
        }

        $validated['user_id'] = $user->id;
        $validated['statcode'] = "REQ";

        if ($dataREQ) {
            return redirect('/role/request')->with('danger', 'You Already Have Pending Request!');
        } elseif ($lastdatastat && $lastdatastat->statcode === "APV") {
            // Update the existing record
            permission::create($validated);
            return redirect('/role/request')->with('success', 'Changing Role Request Has Been Submitted!');
        } elseif ($lastdatastat && $lastdatastat->statcode === "RJC") {
            // Use updateOrCreate instead of update
            permission::create($validated);
            return redirect('/role/request')->with('success', 'Role Request Again Has Been Submitted!');
        }

        // Check other conditions and update or create permission records accordingly
        foreach ($request->input('available_days', []) as $day => $value) {
            AvailableTime::updateOrCreate(
                ['user_id' => auth()->user()->id, 'day' => $day],
                ['start_time' => $request->input('available_time_start', '00:00'), 'end_time' => $request->input('available_time_end', '23:59')]
            );
        }

        // Check for an existing record based on 'norekening' before creating a new one
        $existingRecord = permission::where('norekening', $request->input('norekening'))->first();

        if (!$existingRecord) {
            // Create a new permission record only if no existing record with the same 'norekening' is found
            permission::create($validated);

            // Update or create AvailableTime records

            return redirect('/role/request')->with('success', 'Request Has Been Submitted!');
        }

        return redirect('/role/request')->with('danger', 'A record with the same "norekening" already exists!');
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
