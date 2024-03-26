@extends('layouts/main')
<!-- <link rel="stylesheet" href="/css/order.css"> -->

@section('container')

<form id="scheduleForm" action="/save-schedule-and-cart" method="POST" class="mx-5 mt-4">
    @csrf
    <h1 class="h2-title-text mb-4">ADD TO CART</h1>
    <hr>

    <input type="hidden" name="user_id" value="{{ $user->id }}">
    {{-- <input type="hidden" name="schedule_id" value="{{ session('schedule_id') }}"> --}}
    <input type="hidden" name="temp_schedule" id="temp_schedule" value="">
    <!-- Input tersembunyi untuk menyimpan jadwal sementara -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#scheduleModal">
        Schedule
    </button>
    <div class="card overflow-hidden">
        <table id="cart" class="table table-hover table-condensed text-white card-body">
            <thead>
                <tr>
                    <th style="width:50%">Product</th>
                    <th style="width:10%">Price</th>
                    <th style="width:40%">Schedule</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs">  <img src="{{ asset('storage/' . $image->imageprofile) }}" alt="player Image"
                                class="d-block w-100"></div>
                            <div class="col-sm-9">
                                <h4 class="nomargin" style="color: black">{{ $user->role->name }}</h4>
                                <p>{{ $user->username }}</p>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price"> <img src="/img/gatcha.png" style="height:1.25rem" alt="" class="me-2" />
                        {{ $user->price }}</td>
                    <td data-th="Schedule"> </td> <!-- Tempat untuk menampilkan jadwal yang dipilih -->
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="price" id="price" value="{{ $user->price }}">
    </div>

    <div class="w-100 d-flex justify-content-end">
        <button type="submit" class="btn btn-warning mt-3"><i class="fa fa-angle-left"></i>
            Continue Shopping
            <i class="bi bi-caret-right-fill"></i>
        </button>
    </div>

</form>

<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Schedule Meeting</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="scheduleFormModal">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="selectedTime">Time</label>
                        <select id="timeSlot" class="form-control" name="selectedTime">
                            @foreach($availableTimes as $availableTime)
                            @php
                            $startTime = strtotime($availableTime->start_time);
                            $endTime = strtotime($availableTime->end_time);
                            @endphp
                            @for ($i = $startTime; $i < $endTime; $i +=7200) @php $timeStart=date("H:i", $i);
                                $timeEnd=date("H:i", $i + 7200); @endphp <option value="{{ $timeStart }}">{{ $timeStart
                                }} - {{ $timeEnd }}</option>
                                @endfor
                                @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveTempSchedule(event)">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    function validateSelectedDate(date) {
    const selectedDate = new Date(date);
    const today = new Date();
    const availableDays = [
                        @foreach($availableTimes as $availableTime)
                            '{{ date("D", strtotime($availableTime->day)) }}',
                        @endforeach
                    ];
    // Check if the selected date is today or a future date
    if (selectedDate < today) {
        alert('Please select a date that is today or a future date.');
        return false;
    }

    // Check if the selected date corresponds to an available day
    const selectedDay = selectedDate.toLocaleDateString('en-US', { weekday: 'short' });
    if (!availableDays.includes(selectedDay)) {
        alert('Please select a date that corresponds to an available day.');
        return false;
    }

    return true;
}
function saveTempSchedule(event) {
    event.preventDefault();
    const date = document.getElementById('date').value;
    const time = document.getElementById('timeSlot').value;
    const schedule = { date, time };

    // Save schedule to local storage
    localStorage.setItem('tempSchedule', JSON.stringify(schedule));

    // Update the display on the page (optional)
    updateScheduleDisplay();

    // Close the modal
    $('#scheduleModal').modal('hide');
}

// Function to update the schedule display on the page (optional)
function updateScheduleDisplay() {
    const schedule = JSON.parse(localStorage.getItem('tempSchedule'));

    // Update the table cell with the selected schedule
    const scheduleCell = document.querySelector('td[data-th="Schedule"]');
    scheduleCell.textContent = `${schedule.date} - ${schedule.time}`;
}

// Event listener for the "Continue Shopping" button
document.getElementById('scheduleForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const date = document.getElementById('date').value;
    const schedule = JSON.parse(localStorage.getItem('tempSchedule'));

    if (schedule && validateSelectedDate(date)) {
        const scheduleInput = document.getElementById('temp_schedule');
        scheduleInput.value = JSON.stringify(schedule);

        // Include schedule_id in the form data
        saveScheduleAndCart(schedule);
    }
});

// Function to save schedule and add to cart using AJAX
    function saveScheduleAndCart(schedule) {
        $.ajax({
            type: 'POST',
            url: '/save-schedule-and-cart', // Adjust to the correct route in Laravel
            data: {
                _token: '{{ csrf_token() }}',
                user_id: '{{ $user->id }}',
                price: '{{ $user->price }}',
                schedule: schedule
            },
            success: function (response) {
                console.log(response);

                // Check for 'ok' key in the response
                if (response.ok) {
                    // Display success message
                    alert('Schedule and Cart saved successfully!');

                    // Redirect to the destination page (optional)
                    window.location.href = '/game';
                } else {
                    // Display error message
                    alert(response.message || 'Failed to save schedule and add to cart. Please try again.');
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

// Optional: Load and display the schedule if available in local storage
document.addEventListener('DOMContentLoaded', function () {
    updateScheduleDisplay();
});
</script>
@endsection
