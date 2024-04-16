@extends('layouts.main')

@section('container')
<div class="container mt-4">
    <h1 class="h2-title-text mb-4">ORDER HISTORY</h1>
    <hr>

    @if(session()->has('success'))
    <div class="alert alert-success" role="alert">
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
                    <td>{{ $status[$loop ->iteration-1]}}</td>
                    @if ($transaction->status === 'ON_GOING')
                        <td>
                            <form action="{{ route('transactions.markAsDone', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">Mark as Done</button>
                            </form>
                        </td>
                    @else
                        <td></td>
                    @endif

                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
