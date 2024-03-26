<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(){

        $scammingReports = Report::where('header', 'Scamming')
        ->where('is_refund', false)
        ->where('is_reject', false)
        ->get();
        return view('admin.reports.index', [
            'title' => "Id Card Role",
            'active' => 'Id Card Role',
            'users' => User::where('report_times', '>', 0)->get(),
            'scammingReports' => $scammingReports
        ]);
    }

    public function index_detail(User $user){


        // dd($scheduleCount);
        return view('admin.reports.detail', [
            'active' => 'report_detail',
            'user' => $user,
            'reports'=> Report::where('user_id',$user->id)->get()
            // Post::find($id)

        ]);
    }

    public function acceptReport($reportId)
    {
        // Find the report by ID
        $report = Report::findOrFail($reportId);

        // Assuming the buyer is the user who reported and the seller is the user in the product
        $buyer = $report->buyer;
        $seller = $report->user;

        $seller = User::find($report->user_id);

        // Check if the seller exists
        if (!$seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }

        // Check if the seller has a valid price
        if (!$seller->price) {
            return redirect()->back()->with('error', 'Seller does not have a valid price.');
        }
        $pointsToAdd = $seller->price;

        // Update points
        $buyer->update(['points' => $buyer->points + $pointsToAdd]);

        // $buyer->points += $pointsToAdd;

        $report->update(['is_refund' => true]);


        return redirect()->back()->with('success', 'Report accepted successfully.');
    }

    public function rejectReport($reportId)
    {
        // Find the report by ID
        $report = Report::findOrFail($reportId);


        $report->update(['is_reject' => true]);


        return redirect()->back()->with('success', 'Report rejected successfully.');
    }
}
