@extends('layouts/main')

@section('container')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                @if (session()->has('success'))
                <div class="alert alert-success text-left" role="alert">
                    {{ session('success') }}
                </div>
            @endif
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-black">Update Available Times</h2>

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
                                                $isChecked = $userAvailableTimes->contains('day', $dayName);
                                            @endphp
                                            <label for="{{ $dayName }}" class="form-check-label text-black">
                                                {{ $dayName }}<br>
                                                <input type="checkbox" name="available_days[{{ $dayName }}]" value="{{ $dayName }}" {{ $isChecked ? 'checked' : '' }}>
                                            </label>
                                        @endfor
                                        <div class="form-floating mt-2">
                                            <div class="text-white">
                                                <label for="available_times">Choose Time</label>
                                            </div>
                                            <select name="available_time_start" class="form-select mb-2">
                                                @for ($hour = 0; $hour <= 23; $hour++)
                                                    @for ($minute = 0; $minute <= 59; $minute += 15)
                                                        @php
                                                            $timeValue = sprintf('%02d:%02d:00', $hour, $minute);
                                                        @endphp
                                                        <option value="{{ $timeValue }}" {{ in_array($timeValue, $userAvailableTimes->pluck('start_time')->toArray()) ? 'selected' : '' }}>
                                                            {{ $timeValue }}
                                                        </option>
                                                    @endfor
                                                @endfor
                                            </select>
                                            <select name="available_time_end" class="form-select mb-2">
                                                @for ($hour = 0; $hour <= 23; $hour++)
                                                    @for ($minute = 0; $minute <= 59; $minute += 15)
                                                        @php
                                                            $timeValue = sprintf('%02d:%02d:00', $hour, $minute);
                                                        @endphp
                                                        <option value="{{ $timeValue }}" {{ in_array($timeValue, $userAvailableTimes->pluck('end_time')->toArray()) ? 'selected' : '' }}>
                                                            {{ $timeValue }}
                                                        </option>
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
