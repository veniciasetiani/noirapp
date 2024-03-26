@extends('layouts/main')
@section('container')
@if(auth()->user()->idcardstatcode == 'REQ')
@if(session()->has('danger'))
      <div class="alert alert-danger col-lg-5" role="alert">
          {{ session('danger') }}
      </div>
@else
<div class="row justify-content-center text-center">
    <div class="col-md-12 mt-5">
      <div class="alert alert-success col-md-12" role="alert">
          <h3 style="color: #141432">Your Id Card Number Has Not Been Approved Yet!</h3>
      </div>
    </div>
</div>
@endif
@elseif (auth()->user()->idcardstatcode == 'RJC')

    <div class="row justify-content-center text-center">
        <div class="col-md-12 mt-5">
        <form action="/idcardnumber/request" method="POST">
        @csrf
        <div class="alert alert-danger col-md-12" role="alert">
            <h3 style="color: #141432">Your Id Card Number Has Been Rejected, Please Request Again</h3>
        </div>
        </div>
        <div class="col-md-4 mt-5 text-center">
            <div class="form-floating">
                <input value="{{ old('idcardnumber') }}" type="number" class="mb-2 form-control  @error('idcardnumber') is-invalid @enderror rounded-bottom" id="idcardnumber" placeholder="idcardnumber" name="idcardnumber">
                <label for="idcardnumber">Identity Card Number</label>
                @error('idcardnumber')
                <div class="invalid-feedback">{{$message }}</div>
                @enderror

            </div>
        </div>
    </div>

    <div class="row justify-content-center text-center">
        <div class="col-md-5">

        <button class="btn btn-primary w-30 " type="submit" id="idnumcardreq">Register</button>
    </div>

    </div>
</form>

@else
    <section class="contact-us-light-lick position-relative">
        <section class="h-100 w-100" style="box-sizing: border-box; background-color: #141432; min-height: 100vh">
            <style scoped>
                @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

                .content-3-3 .btn:focus,
                .content-3-3 .btn:active {
                    outline: none !important;
                }

                .content-3-3 {
                    padding: 5rem 2rem;
                }

                .content-3-3 .img-hero {
                    width: 100%;
                    margin-bottom: 3rem;
                }

                .content-3-3 .right-column {
                    width: 100%;
                }

                .content-3-3 .title-text {
                    font: 600 1.875rem/2.25rem Poppins, sans-serif;
                    margin-bottom: 2.5rem;
                    letter-spacing: -0.025em;
                }

                .content-3-3 .title-caption {
                    font: 500 1.5rem/2rem Poppins, sans-serif;
                    margin-bottom: 1.25rem;
                }

                .content-3-3 .circle {
                    font: 500 1.25rem/1.75rem Poppins, sans-serif;
                    height: 3rem;
                    width: 3rem;
                    margin-bottom: 1.25rem;
                    border-radius: 9999px;
                    background-color: #504cee;
                }

                .content-3-3 .text-caption {
                    font: 400 1rem/1.75rem Poppins, sans-serif;
                    letter-spacing: 0.025em;
                    color: #999999;
                }

                .content-3-3 .btn-learn {
                    font: 600 1rem/1.5rem Poppins, sans-serif;
                    padding: 1rem 2.5rem;
                    background-color: #504cee;
                    transition: 0.3s;
                    letter-spacing: 0.025em;
                    border-radius: 0.75rem;
                }

                .content-3-3 .btn-learn:hover {
                    background-color: #7370ff;
                    transition: 0.3s;
                }

                @media (min-width: 768px) {
                    .content-3-3 .title-text {
                        font: 600 2.25rem/2.5rem Poppins, sans-serif;
                    }
                }

                @media (min-width: 992px) {
                    .content-3-3 .img-hero {
                        width: 50%;
                        margin-bottom: 0;
                    }

                    .content-3-3 .right-column {
                        width: 50%;
                    }

                    .content-3-3 .circle {
                        margin-right: 1.25rem;
                        margin-bottom: 0;
                    }
                }
            </style>
            <div class="content-3-3 container-xxl mx-auto position-relative" style="font-family: 'Poppins', sans-serif">
                <div class="d-flex flex-lg-row flex-column align-items-center">
                    <!-- Left Column -->
                    <div class="img-hero text-center justify-content-center d-flex">
                        <img id="hero" class="img-fluid"
                            src="http://api.elements.buildwithangga.com/storage/files/2/assets/Content/Content3/Content-3-1.png"
                            alt="" />
                    </div>

                    <!-- Right Column -->
                    <div
                        class="right-column d-flex flex-column align-items-lg-start align-items-center text-lg-start text-center">
                        <form id="topUpForm">
                            @csrf
                            <h2 class="title-text text-white mb-4">Top Up Page</h2>
                            <ul style="padding: 0; margin: 0">
                                <li class="list-unstyled" style="margin-bottom: 2rem">
                                    <p class="text-caption mb-2">
                                        Poin
                                    </p>
                                    <h4
                                        class="title-caption text-white d-flex flex-lg-row flex-column align-items-center justify-content-lg-start justify-content-center">
                                        <select class="form-select" aria-label="Default select example" name="point_top_up">
                                            <option selected>Gacha point</option>
                                            <option value="50">50 Point</option>
                                            <option value="100">100 Point</option>
                                            <option value="150">150 Point</option>
                                            <option value="500">500 Point</option>
                                            <option value="1000">1000 Point</option>
                                        </select>
                                    </h4>

                                </li>
                                <li class="list-unstyled" style="margin-bottom: 2rem">
                                    <p class="text-caption mb-2">
                                        Payment method
                                    </p>
                                    <h4
                                        class="title-caption text-white d-flex flex-lg-row flex-column align-items-center justify-content-lg-start justify-content-center">
                                        <select class="form-select" aria-label="Default select example"
                                            name="payment_method">
                                            <option selected>Please choose payment method!</option>
                                            <option value="bca">BCA</option>
                                            <option value="mandiri">Mandiri</option>
                                            <option value="gopay">Gopay</option>
                                        </select>
                                    </h4>
                                    <input type="hidden" name="username" value="{{ auth()->user()->username }}">
                                    <input type="hidden" name="status" value="paid">
                                </li>
                            </ul>
                            <button type="button" class="btn btn-learn text-white" onclick="showTopUpModal()">Purchase</button>

                            {{-- <button type="submit" class="btn btn-learn text-white">Purchase</button> --}}

                            <div class="modal fade" id="topUpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Top Up Summary</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Selected Points:</strong> <span id="selectedPoints"></span></p>
                                            <p><strong>Total Amount:</strong> <span id="totalAmount"></span> IDR</p>

                                            <!-- BCA Virtual Account UI -->
                                            <div class="mb-3">
                                                <label for="bcaAccountNumber" class="form-label">BCA Virtual Account</label>
                                                <input type="text" class="form-control" id="bcaAccountNumber" value="1234-5678-9012" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="bcaExpiration" class="form-label">Expiration Date</label>
                                                <input type="text" class="form-control" id="bcaExpiration" value="01/25" readonly>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button id="topUpButton" type="submit" class="btn btn-primary">Purchase</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

    function showTopUpModal() {
        // Get selected points from the dropdown
        var selectedPoints = document.querySelector('[name="point_top_up"]').value;
        // Get selected payment method
        var paymentMethod = document.querySelector('[name="payment_method"]').value;

        if (selectedPoints !== 'Gacha point' && paymentMethod !== 'Please choose payment method!') {
            // Calculate total amount in IDR (assuming 200 IDR per point)
            var totalAmount = selectedPoints * 200;

            // Generate BCA virtual account details (replace with actual logic if needed)
            var bcaAccountNumber = "1234-5678-9012";
            var bcaExpiration = "01/25";

            // Update modal content
            document.getElementById('selectedPoints').textContent = selectedPoints + " Point";
            document.getElementById('totalAmount').textContent = totalAmount;
            document.getElementById('bcaAccountNumber').value = bcaAccountNumber;
            document.getElementById('bcaExpiration').value = bcaExpiration;

            // Show the modal
            var topUpModal = new bootstrap.Modal(document.getElementById('topUpModal'));
            topUpModal.show();
        } else {
            // Clear modal content if already filled
            document.getElementById('selectedPoints').textContent = '';
            document.getElementById('totalAmount').textContent = '';
            document.getElementById('bcaAccountNumber').value = '';
            document.getElementById('bcaExpiration').value = '';

            // Alert the user to select both Point and Payment Method
            alert('Please select both Point and Payment Method.');
        }
    }

        const authenticatedUsername = "{{ auth()->user()->username }}";


        function redirectToCart() {
            const cartUrl = `/cart/${authenticatedUsername}`;
            window.location.href = cartUrl;
        }


        const urlParams = new URLSearchParams(window.location.search);
        const fromCart = urlParams.get('from_cart');


        function redirectToTopUp() {
            if (fromCart || fromCart == "true") {
                redirectToCart();
            } else {
                window.location.href = "{{ route('topup.sukses') }}";
            }
        }


        $('#topUpButton').click(function(e) {
        e.preventDefault();
        alert('Saldo Anda tidak cukup');
    });
    </script>

@endif
@endsection
