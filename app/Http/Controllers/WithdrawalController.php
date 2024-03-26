<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('withdraw.withdrawal',
        [
            "title" => "withdrawal gatcha",
            'active' => 'withdrawal gatcha',

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
        if($request->Withdrawal > auth()->user()->points){
            return redirect('/withdrawal')->with('error', 'Withdrawal Exceed The Maximum Balance!');
        }
        $data =  User::find(auth()->user()->id);
        $validated["points"] = $data->points - $request->Withdrawal;

        $user = auth()->user();
        $passwordMatches = Hash::check($request->input('password'), $user->password);
        if (!$passwordMatches) {
            return redirect('/withdrawal')->with('error', 'Incorrect password. Withdrawal failed.');
        }

        User::where('id',auth()->user()->id)
        ->update($validated);

        $request -> session() ->flash('success','Withdrawal Successful , Please Check Your Bank!');
        return redirect('/withdrawal');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\point  $point
     * @return \Illuminate\Http\Response
     */
    public function show(point $point)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\point  $point
     * @return \Illuminate\Http\Response
     */
    public function edit(point $point)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\point  $point
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, point $point)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\point  $point
     * @return \Illuminate\Http\Response
     */
    public function destroy(point $point)
    {
        //
    }
}
