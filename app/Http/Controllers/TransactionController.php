<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
{
    $transactions = Transaction::where('seller_id', auth()->user()->id)
                               ->where('status', 'ON_GOING')
                               ->get();

    return view('transaction.transactions', compact('transactions'),['active' => 'transactionPage']);
}

public function markAsDone($id)
{
    $transaction = Transaction::findOrFail($id);
    $transaction->status = 'DONE';
    $transaction->save();

    return redirect()->route('transactions.index')->with('success', 'Transaction marked as done.');
}

    public function history(){
        $transactions = Transaction::where('buyer_id', auth()->user()->id)
            ->where('status', ['ON_GOING', 'DONE'])
            ->get();

        return view('transaction.history', compact('transactions'),['active' => 'historyPage']);
    }
}
