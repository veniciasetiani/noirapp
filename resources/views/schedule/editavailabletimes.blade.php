@extends('layouts/main')

@section('container')
    <div class="container">
        @if (session()->has('success'))
        <div class="alert alert-success col-lg-5" role="alert">
            {{ session('success') }}
        </div>
    @endif
        <div class="row justify-content-center my-5">
            <div class="col-md-6">
                <div class="card card-default-color">
                    <div class="card-body">
                        <h2 class="card-title">Update Available Times</h2>

                        <form action="/updateavailabletimes" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-floating mt-2">
                                <div class="text-white">
                                    <label for="available_times">Available Times</label>
                                </div>

                            <div class="row">
                                <div class="col">
                                    @for ($i = 0; $i < 7; $i++)
                                        @php
                                            $dayName = date('l', strtotime("Sunday +$i days"));
                                        @endphp
                                        <label for="{{ $dayName }}" class="form-check-label text-black">
                                            {{ $dayName }}<br>
                                            <input type="checkbox" name="available_days[{{ $dayName }}]" value="{{ $dayName }}">
                                        </label>
                                    @endfor
                                    <div class="form-floating mt-2">
                                        <div class="text-white">
                                            <label for="available_times">Choose Time</label>
                                        </div>
                                        <select name="available_time_start" class="form-select mb-2">
                                            @for ($hour = 0; $hour <= 23; $hour++)
                                                @for ($minute = 0; $minute <= 59; $minute += 15)
                                                    <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">{{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                        <select name="available_time_end" class="form-select mb-2">
                                            @for ($hour = 0; $hour <= 23; $hour++)
                                                @for ($minute = 0; $minute <= 59; $minute += 15)
                                                    <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">{{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
