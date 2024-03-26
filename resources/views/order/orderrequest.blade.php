@extends('layouts/main')

@section('container')
    <div class="container mt-4">
        <h1 class="h2-title-text mb-4">ORDER REQUEST</h1>
        <hr>

        @if ($orderValidations->isEmpty())
            <p class="text-center text-danger">No order requests available.</p>
        @else
            <table class="table table-bordered table-white-text text-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Buyer</th>
                        <th>Price</th>
                        <th>Schedule</th> <!-- Ganti Quantity dengan Schedule -->
                        <th>Status</th>
                        <th>Action</th>
                        <th>Timer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderValidations as $orderValidation)
                            <td>{{ $loop ->iteration }}</td>
                            <td>{{ $orderValidation->buyer->name }}</td>
                            <td>{{ $orderValidation->price }}</td>
                            <td>
                                @if ($orderValidation->schedule)
                                    Date: {{ $orderValidation->schedule->date }},
                                    Time: {{ $orderValidation->schedule->start_time }} - {{ $orderValidation->schedule->end_time }}
                                @endif
                            </td>
                            <td>{{ $orderValidation->status }}</td>
                            <td>
                                <form action="{{ route('order.process', $orderValidation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Accept</button>
                                </form>
                                <form action="{{ route('order.reject', $orderValidation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" style="margin-top: 6px">Reject</button>
                                </form>
                            </td>

                             <td><span class="expiry-time" data-expiry="{{ $orderValidation->timer_expiry }}" data-item-id="{{ $orderValidation->id }}"></span></td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const expiryElements = document.querySelectorAll('.expiry-time');
            const countdownTimers = []; // Variabel untuk menyimpan timers

            expiryElements.forEach((expiryElement, index) => {
                let expiryTime = new Date(expiryElement.dataset.expiry); // Ambil waktu kedaluwarsa dari HTML

                function updateExpiryDisplay() {
                    const now = new Date();
                    const timeLeft = Math.floor((expiryTime - now) / 1000); // Calculate remaining time in seconds

                    if (timeLeft <= 0) {
                        clearInterval(countdownTimers[index]);
                        expiryElement.textContent = 'Expired';
                        expiryElement.style.color = 'red';
                        alert('Order Request Expired!');
                        // Remove the item row from the view
                        const rejectButton = expiryElement.closest('tr').querySelector('.btn-danger');
                        if (rejectButton) {
                            rejectButton.click();
                        }

                    } else {
                        const hours = Math.floor(timeLeft / 3600);
                        const minutes = Math.floor((timeLeft % 3600) / 60);
                        const seconds = timeLeft % 60;
                        expiryElement.textContent = `${padWithZero(hours)}:${padWithZero(minutes)}:${padWithZero(seconds)}`;
                    }
                }
                function padWithZero(number) {
                    return (number < 10 ? '0' : '') + number; // Tambahkan 0 di depan jika angka kurang dari 10
                }

                updateExpiryDisplay();
                countdownTimers[index] = setInterval(updateExpiryDisplay, 1000); // Simpan timer dalam array
            });
        });
    </script>
@endsection
