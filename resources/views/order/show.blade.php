@extends('layouts/main')

@section('container')
<div class="container my-3 py-5">
    <h2 class="text-white text-center text-title-menu">My Cart</h2>

    @if(session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card bg-transparent my-2 border-0">
        <div class="card-body">
            <table class="table table-white-text">
                <thead>
                    <tr>
                        <th class="white-text text-center">Check</th>
                        <th class="white-text text-center">Product</th>
                        <th class="white-text text-center">Price</th>
                        <th class="white-text text-center">Schedule</th>
                        <th class="white-text text-center">Actions</th>
                        <th class="white-text text-center">Timer</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalPrice = 0; // Initialize total price outside the loop
                    @endphp

                    @for ($i = 0; $i < count($cart); $i++)
                        @php
                            $cartItem = $cart[$i];
                        @endphp

                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="item-checkbox" data-item-id="{{ $cartItem->id }}" data-seller-name="{{ $cartItem->seller->name }}" data-price="{{ $cartItem->price }}" data-schedule-id="{{ $cartItem->schedule_id }}" name="selectedItems[]" value="{{ $cartItem->id }}">
                            </td>
                            <td class="text-center">{{ $cartItem->seller->name }}</td>
                            <td class="text-center">{{ $cartItem->price }}</td>
                            <td class="text-center">
                                @if ($cartItem->schedule)
                                    Date: {{ $cartItem->schedule->date }},
                                    Time: {{ $cartItem->schedule->start_time }} - {{ $cartItem->schedule->end_time }}
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="/addtocart/{{ $cartItem->id }}" method="POST" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button class="badge bg-danger border-0" onclick="return confirm('are you sure deleting this?')"><span class="bi bi-trash " style="color: white"></span></button>
                                  </form>
                                {{-- <a href="#" class="badge bg-danger border-0 delete-item" data-item-id="{{ $cartItem->id }}"><span class="bi bi-trash" style="color: white"></span></a> --}}
                            </td>

                             {{-- <td class="text-center">{{ $cartItem->timer_expiry }}</td> --}}
                            {{-- <td><div id="timer"></div></td> --}}
                            <td class="text-center"><span class="expiry-time" data-expiry="{{ $cartItem->timer_expiry }}" data-item-id="{{ $cartItem->id }}"></span></td>

                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">
        <h3>Total Token: <span id="grand-total">{{ number_format($totalPrice, 2) }}</span></h3>
    </div>

    <div class="text-center">
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#orderModal">Place Order</button>
    </div>
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ms-auto" id="orderModalLabel">Order Summary</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Total Price: <span id="modal-total-price"></span></p>
                    <p>User Points: <span id="modal-user-points"></span></p>
                    <p id="insufficient-points-message" style="color: red;"></p>
                </div>
                <div class="modal-footer">
                    <button id="top-up-button" class="btn btn-primary" style="display: none;">Top Up</button> <!-- New button for top-up -->
                    <form id="placeOrderForm" method="POST" class="d-flex justify-content-center">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="place-order-button">Ok</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    const expiryElements = document.querySelectorAll('.expiry-time');
    const countdownTimers = []; // Variabel untuk menyimpan timers

    expiryElements.forEach((expiryElement, index) => {
        let expiryTime = new Date(expiryElement.dataset.expiry); // Ambil waktu kedaluwarsa dari HTML

        function updateExpiryDisplay() {
            const now = new Date();
            const timeLeft = Math.floor((expiryTime - now) / 1000); // Hitung waktu yang tersisa dalam detik

            if (timeLeft <= 0) {
                clearInterval(countdownTimers[index]);
                expiryElement.textContent = 'Expired';
                alert('Item Expired!');
                // Menghilangkan baris item dari tampilan
                const itemRow = expiryElement.closest('tr');
                if (itemRow) {
                    itemRow.remove();
                }

                const itemId = expiryElement.dataset.itemId;
                // Kirim permintaan DELETE ke backend untuk menghapus item
                fetch(`/addtocart/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(response => {
                    if (response.ok) {
                        // Item berhasil dihapus, lakukan sesuatu (misalnya, tampilkan notifikasi)
                        location.reload();
                        alert('Item Expired!');
                        console.log('Item deleted successfully.');
                    } else {
                        // Handle error
                        console.error('Failed to delete item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            } else {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                expiryElement.textContent = `${padWithZero(minutes)}:${padWithZero(seconds)}`;
            }
        }
        function padWithZero(number) {
            return (number < 10 ? '0' : '') + number; // Tambahkan 0 di depan jika angka kurang dari 10
        }

        updateExpiryDisplay();
        countdownTimers[index] = setInterval(updateExpiryDisplay, 1000); // Simpan timer dalam array
    });
});
// Panggil fungsi untuk memulai timer saat halaman dimuat
// document.addEventListener('DOMContentLoaded', startTimer);

$(document).ready(function() {
    $('form#placeOrderForm').on('submit', function(e) {
        e.preventDefault();

        var selectedItems = [];

        $('.item-checkbox:checked').each(function() {
            selectedItems.push($(this).data('item-id'));
        });

        $.ajax({
            type: 'POST',
            url: '{{ route('place.order') }}',
            data: {
                selectedItems: selectedItems,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                alert('Place Order Successful!');
                $('#orderModal').modal('hide');
                location.reload();
            },
            error: function(error) {
                alert('Error placing order. Please try again later.');
                console.error(error);
            }
        });
    });
});

function updateCart() {
    $.ajax({
        url: '/getcart', // Ganti dengan rute yang tepat untuk mendapatkan data keranjang
        success: function(data) {

            console.log(data);
        },
        complete: function() {
            setTimeout(updateCart, 5000); // Memanggil kembali fungsi ini setiap 5 detik
        }
    });
}
// function submitForm() {
//     var selectedItems = document.querySelectorAll('input.item-checkbox:checked');
//     var itemIds = Array.from(selectedItems).map(item => item.value);
//     // console.log(itemIds);

//     var formData = new FormData();
//     formData.append('selectedItems', itemIds.join(','));

//     fetch('/orderpage', {
//         method: 'POST',
//         body: formData,
//         headers: {
//             'X-CSRF-TOKEN': '{{ csrf_token() }}'
//         }
//     }).then(function(response) {
//         if (!response.ok) {
//             throw new Error('Network response was not ok');
//         }
//         return response.json();
//     }).then(function(data) {
//         // Lakukan manipulasi DOM atau alihkan pengguna ke halaman lain
//         window.location.href = '/orderpage'; // Contoh mengalihkan ke halaman order
//     }).catch(function(error) {
//         console.error('There was a problem with the fetch operation:', error);
//     });

//     return false; // Mengembalikan false untuk mencegah formulir mengirimkan permintaan lagi
// }
    // Get all quantity input fields
    const grandTotalElement = document.getElementById('grand-total');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    // Array to store selected item IDs
    const selectedItems = [];

    // Add change event listener to each checkbox
    itemCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            updateTotals();
            updateSelectedItems();
        });
    });

    // Function to update totals based on checkbox state
    function updateTotals() {
        let grandTotal = 0;

        itemCheckboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const price = parseFloat(checkbox.getAttribute('data-price'));
                grandTotal += price;
            }
        });

        grandTotalElement.textContent = `${grandTotal.toFixed(2)}`;
    }


    // Function to update the list of selected items
    function updateSelectedItems() {
    selectedItems.length = 0; // Clear the array
    let totalPrice = 0; // Initialize total price

    itemCheckboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            const itemId = checkbox.getAttribute('data-item-id');
            const price = parseFloat(checkbox.getAttribute('data-price'));
            selectedItems.push(itemId);
            totalPrice += price;
        }
    });

    // Update modal content
    const userPoints = parseFloat('{{ auth()->user()->points }}');
    const userPointsDisplay = userPoints || userPoints === 0 ? userPoints : '0';

    // Update modal content
    const modalTotalPriceElement = document.getElementById('modal-total-price');
    const modalUserPointsElement = document.getElementById('modal-user-points');

    modalTotalPriceElement.textContent = `${totalPrice.toFixed(2)}`;
    modalUserPointsElement.textContent = userPointsDisplay;

    const insufficientPointsMessage = document.getElementById('insufficient-points-message');
    const topUpButton = document.getElementById('top-up-button');
    const placeOrderButton = document.getElementById('place-order-button');

    modalTotalPriceElement.textContent = `${totalPrice.toFixed(2)}`;
    modalUserPointsElement.textContent = userPointsDisplay;

    // Check if user points are insufficient
    if (userPoints < totalPrice || userPointsDisplay == '0') {
        insufficientPointsMessage.textContent = 'Silahkan top up dulu.';
        topUpButton.style.display = 'inline-block';
        placeOrderButton.style.display = 'none';
    }else {
        insufficientPointsMessage.textContent = ''; // Clear the message if points are sufficient
        topUpButton.style.display = 'none';
        placeOrderButton.style.display = 'inline-block';
    }
    topUpButton.addEventListener('click', function() {
        window.location.href = '/top_up?from_cart=true';
    });
}

    // // Handle the "Place Order" button click
    // const orderButton = document.getElementById('order-button');

    // orderButton.addEventListener('click', function() {
    //     const selectedItems = [];

    //     itemCheckboxes.forEach((checkbox) => {
    //         if (checkbox.checked) {
    //             const itemId = checkbox.getAttribute('data-item-id');
    //             selectedItems.push(itemId);
    //         }
    //     });

    //     if (selectedItems.length === 0) {
    //         alert('Please select at least one item before placing an order.');
    //         return;
    //     }

    //     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    //     fetch('/place-order', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': csrfToken,
    //         },
    //         body: JSON.stringify({ selectedItems }),
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         console.log(data); // Handle response from server if needed

    //         const selectedItemsString = selectedItems.join(',');
    //         const orderPageUrl = `/orderpage?selectedItems=${selectedItemsString}`;

    //         // Redirect to the order page
    //         window.location.href = orderPageUrl;
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //     });
    // });
</script>

<style>
    /* Define the text color for the table content */
    .table-white-text tbody tr td {
        color: white;
    }

    /* Define the text color for the specific header cells */
    .white-text {
        color: white;
    }

    /* Style for the quantity input */
    .quantity-input {
        width: 50px !important; /* Adjust this as needed */
        padding: 0.25rem 0.5rem !important; /* Adjust padding as needed */
        font-size: 14px !important; /* Adjust font size as needed */
    }

    /* Center-align both header and data in the Quantity column */
    .table th.text-center, .table td.text-center {
        text-align: center !important;
    }
</style>
@endsection
