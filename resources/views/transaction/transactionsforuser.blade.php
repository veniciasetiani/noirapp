{{-- BUAT ONGOIN TRANSACTION DI SISI USER --}}
@extends('layouts.main')

@section('container')
<div class="container mt-4">
    <h1 class="h2-title-text mb-4">TRANSACTION</h1>
    <hr>

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    {{-- @if ($transactions->isEmpty())
            <p class="text-center text-danger">No transaction available.</p>
    @else --}}
    <div class="card overflow-hidden">
    <table class="table text-white card-body">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Order For</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop ->iteration }}</td>
                    <td>{{ $transaction->seller->username }}</td>
                    <td>{{ $transaction->seller->role->name}}</td>
                    <td>{{ $transaction->price}}</td>
                    <td>{{ $status[$loop ->iteration-1]}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    {{-- @endif --}}
</div>
@endsection
