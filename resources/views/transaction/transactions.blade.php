@extends('layouts.main')

@section('container')
<div class="container mt-5">
    <h2 class="text-center text-title-menu">Transaction</h2>

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($transactions->isEmpty())
            <p class="text-center text-danger">No transaction available.</p>
    @else
    <table class="table text-white">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Order For</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop ->iteration }}</td>
                    <td>{{ $transaction->buyer->name }}</td>
                    <td>{{ $transaction->seller->role->name}}</td>
                    <td>{{ $transaction->price}}</td>
                    <td>{{ $transaction->status}}</td>
                    <td>
                        <form action="{{ route('transactions.markAsDone', $transaction->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Mark as Done</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
