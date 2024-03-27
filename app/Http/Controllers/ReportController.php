<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(User $user){

//adoako
        // dd($scheduleCount);
        return view('report.report', [
            'active' => 'report',
            'user' => $user
            // Post::find($id)

        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'header' =>'required',
            'detail' => 'required|min:20',
            'image' => 'required|image|file|max:1024',
        ]);

        if($request->file('image')){
            $validated['image'] = $request->file('image')->store('report-images');
        }

        $user_id = $request->user_id;
        $buyer_id = auth()->user()->id;

        $user = User::where('id',$user_id)->first();

        $user-> report_times += 1;
        $user->save();
        Report::create([
            'header' => $request->header,
            'detail' => $request->detail,
            'user_id' => $user_id,
            'buyer_id' => $buyer_id,
            'image' => $request->file('image')->store('report-images')
        ]);


        return redirect()->back()->with('success','Report Has Been Submitted!');
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->ban_status = true;
        $user->save();

        return redirect('/report-users')->with('success', 'User banned successfully');
    }

    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->ban_status = false;
        $user->report_times = 0;
        $user->unban_times += 1;
        $user->save();
        Report::where('user_id', $id)->delete();


        return redirect('/report-users')->with('success', 'User unbanned successfully');
    }
}
