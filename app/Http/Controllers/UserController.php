<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\EditDisplayed;
use App\Models\permission;
use Illuminate\Http\Request;
use App\Models\AvailableTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function showsingleuser(User $user){
        $permissions = DB::table('permissions')
                        ->select('image', 'video','statcode')
                        ->where('user_id', $user->id)
                        ->get();

        $availableTimes = AvailableTime::where('user_id', $user->id)->get();
        $availableDays = $availableTimes->pluck('day')->unique()->values()->toArray();
        // $schedules = Schedule::where('buyer_id',auth()->user()->id)->get();
        // Ambil hari yang tersedia
        // $userSelectedDates = Schedule::where('user_id', $user->id)->pluck('date');
        // $formattedDates = $userSelectedDates->map(function ($date) {
        //     return Carbon::parse($date)->toDateString();
        // });
        // dd($userSelectedDate);

        // dd($selectedDate);
        // $existingTimes = Schedule::where('user_id', $user->id)
        // ->select('start_time', 'end_time')
        // ->get();
        //  dd($existingTimes);

        return view('singleuser',compact('availableTimes','availableDays'), [
            'title' => "User Information",
            'active' => 'singleuser',
            'user' => $user->load('category', 'role', 'cart', 'permission'),
            'permissions' => $permissions,
        ]);

    }

    public function reducePoints(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::where('id',$user_id )->first();
        $totalPrice = $request->input('totalPrice'); // Ganti dengan cara Anda mendapatkan total harga dari request
        $user->points -= $totalPrice;
        $user->save();

        return response()->json(['success' => true]);
    }
    public function availableTimes() {
        return $this->hasMany(AvailableTime::class);
    }


    public function showUpdateForm()
    {
        $active = 'editdisplayeditem';
        return view('updatesingleuser', compact('active')); // Pass the $active variable to the view
    }



public function updateSingleUser(Request $request)
{
    // Validate the request data
    $request->validate([
        'bio' => 'required|string|max:255', // Add more validation rules as needed
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max size 2MB for images
        'video' => 'nullable|mimes:mp4,mov,avi|max:20480', // Max size 20MB for videos
    ]);

    // Create a new update request
    $updateRequest = new EditDisplayed();
    $updateRequest->user_id = auth()->user()->id; // Assuming the user is authenticated
    $updateRequest->bio = $request->input('bio');
    $updateRequest->save();

    if ($request->hasFile('image')) {
        // Store the uploaded image in the storage folder
        $imagePath = $request->file('image')->store('profile_images', 'public');

        // Save the image path to the update request record
        $updateRequest->image_path = $imagePath;
        $updateRequest->save();
    }

    // Handle video upload if provided
    if ($request->hasFile('video')) {
        // Store the uploaded video in the storage folder
        $videoPath = $request->file('video')->store('profile_videos', 'public');

        // Save the video path to the update request record
        $updateRequest->video_path = $videoPath;
        $updateRequest->save();
    }


    // Redirect the user back with a success message or show a confirmation message
    return redirect('/updatesingleuser')->with('success', 'Update request submitted successfully. Waiting for admin approval.');
}

public function showRequestDetails($requestId)
{
    $roleRequest = permission::find($requestId);
    $updateRequest = EditDisplayed::find($requestId);

    if ($roleRequest && $roleRequest->statcode === 'APV') {
        // Role request is approved, display image and video
        return view('request_details', ['image' => $roleRequest->image_path, 'video' => $roleRequest->video_path]);
    } elseif ($updateRequest && $updateRequest->statcode === 'APV') {
        // Update request is approved, display updated data
        return view('request_details', ['bio' => $updateRequest->bio, 'image' => $updateRequest->image_path, 'video' => $updateRequest->video_path]);
    } else {
        // Handle other cases or show an error message
        return view('error_page');
    }
}

}
