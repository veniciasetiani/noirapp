<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\AvailableTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function showSchedule($user_id)
{
    $availableTimes = AvailableTime::where('user_id', $user_id)->get();
    $availableDates = $availableTimes->pluck('day')->unique(); // Ambil tanggal yang tersedia

    return view('schedule', ['active' => 'register', 'availableTimes' => $availableTimes, 'availableDates' => $availableDates]);
}
    public function saveSchedule(Request $request) {
        $userId = $request->input('user_id');
        $date = $request->input('date');
        $time = $request->input('time');

        Schedule::create([
            'user_id' => $userId,
            'date' => $date,
            'time' => $time,
        ]);

        return back()->with('success', 'Tanggal dan waktu telah disimpan.');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required', // Pastikan user_id tersedia dalam request
            'date' => 'required|date',
            'selectedTime' => 'required', // Pastikan selectedTime tersedia dalam request
        ]);
        // $buyer_id = auth()->user()->id;
        $selectedDate = $request->input('date');
        $selectedTime = $request->input('selectedTime');
        // $existingSchedule = Schedule::where('user_id',$request->input('user_id'))->where('date', $selectedDate)
        // ->where('start_time', $selectedTime)
        // ->exists();
        // $existingSchedule2 = Schedule::where('buyer_id', $buyer_id)->first();

        // if ($existingSchedule2) {
        //     return redirect()->back()->with('error', 'You already have a schedule.');
        // }
        // if ($existingSchedule) {
        //     return redirect()->back()->with('error', 'The selected date and time are not available.');
        // }
        $user_id = $validated['user_id'];
        $buyer_id = auth()->user()->id;
        $date = $validated['date'];
        $start_time = $validated['selectedTime'];
        $end_time = date('H:i', strtotime($start_time) + 7200); // Menambah 2 jam ke waktu mulai
        $existingSchedule = Schedule::where('user_id',$user_id)->where('date', $date)
        ->where('start_time', $start_time)

        ->exists();
        $existingSchedule2 = Schedule::where('buyer_id', $buyer_id)->where('is_active', true)->first();

        if ($existingSchedule2) {
            return redirect()->back()->with('error', 'You already have a schedule.');
        }
        if ($existingSchedule) {
            return redirect()->back()->with('error', 'The selected date and time are not available.');
        }
        $newSchedule = Schedule::create([
            'user_id' => $user_id,
            'buyer_id' => $buyer_id,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);

        return redirect()->back()->with('schedule_id', $newSchedule->id)->with('success', 'Schedule saved successfully!');
    }


    public function saveSchedules(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'schedule' => 'required|array',
            'schedule.date' => 'required|date',
            'schedule.time' => 'required'
        ]);

        $user_id = $validated['user_id'];
        $buyer_id = auth()->user()->id;
        $date = $validated['schedule']['date'];
        $time = $validated['schedule']['time'];

        // Lakukan penyimpanan jadwal ke database sesuai kebutuhan Anda
        // ...
        $existingSchedule = Schedule::where('user_id',$user_id)->where('date', $date)
        ->where('start_time', $time)
        ->where('is_active', true)
        ->exists();
        $existingSchedule2 = Schedule::where('buyer_id', $buyer_id)->where('is_active', true)->first();

        if ($existingSchedule2) {
            return redirect()->back()->with('error', 'You already have a schedule.');
        }
        if ($existingSchedule) {
            return redirect()->back()->with('error', 'The selected date and time are not available.');
        }
        try{
            $schedule = new Schedule([
                'user_id' => $user_id,
                'buyer_id' => $buyer_id,
                'date' => $date,
                'start_time' => $time,
                'end_time' => date('H:i', strtotime($time) + 7200),
            ]);

            $schedule->save();

            return redirect()->back()->with('schedule_id', $schedule->id)->with('success', 'Schedule saved successfully!');
        }catch (\Exception $e) {
            Log::error('Error saving schedule: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save schedule. Please try again.']);
        }
        // Contoh penyimpanan jadwal menggunakan model Schedule

    }


    public function userSchedules()
    {
        $userSchedules = Schedule::where('buyer_id',auth()->user()->id)->get();

        return view('schedule.userschedule', ['schedules' => $userSchedules,'active' => 'userschedule']);
    }

    public function sellerSchedules()
    {
        $userSchedules = Schedule::where('user_id',auth()->user()->id)->where('is_active',true)->get();

        return view('schedule.sellerschedule', ['schedules' => $userSchedules,'active' => 'userschedule']);
    }

    public function showEditSchedule()
    {
        $user = auth()->user(); // Get the authenticated user

        // Fetch the user's available times from the database
        $userAvailableTimes = AvailableTime::where('user_id', $user->id)->get();

        $active = 'editschedule';

        return view('schedule.editavailabletimes', compact('userAvailableTimes', 'active'));
    }

    public function updateSchedule(Request $request)
    {
        $user_id = auth()->user()->id;

        AvailableTime::where('user_id', $user_id)->delete();


        foreach ($request->input('available_days', []) as $day => $value) {
            AvailableTime::updateOrCreate(
                ['user_id' => auth()->user()->id, 'day' => $day],
                ['start_time' => $request->input('available_time_start', '00:00'), 'end_time' => $request->input('available_time_end', '23:59')]
            );
        }
        // $availableTimes = AvailableTime::find($user_id);

        // foreach ($request->input('available_days', []) as $day => $value) {

        //     $availableTimes->day = $day;
        //     $availableTimes->start_time = $request->input('available_time_start', '00:00');
        //     $availableTimes->end_time = $request->input('available_time_end', '23:59');
        //     $availableTimes->save();
        // }
        return redirect()->route('schedule.viewedit')->with('success', 'Update Schedule successfully.');
    }
}
