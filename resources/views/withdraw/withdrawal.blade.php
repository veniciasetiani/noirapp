@extends('layouts/main')
@section('container')

    <div class="row justify-content-center text-center">
        <div class="col-md-12 mt-5">
            <form action="/withdrawal" method="POST">
            @csrf
            @if(session()->has('error'))
            <div class="alert alert-danger col-md-12" role="alert">
                <h3 style="color: #141432"> {{ session('error') }}</h3>
            </div>
            @elseif(session()->has('success'))
            <div class="alert alert-success col-md-12" role="alert">
                <h3 style="color: #141432"> {{ session('success') }}</h3>
            </div>
            @else
            <div class="alert alert-primary col-md-12" role="alert">
                <h3 style="color: #141432">Withdrawal Request</h3>
            </div>
            @endif

        </div>
        <div class="text-white">
            <label for="Withdrawal">Your Gacha Balance Now : {{ auth()->user()->points == null | 0 ? 0 : auth()->user()->points }}</label>

        </div>
        <div class="col-md-4 mt-5 text-center">
            <div class="form-floating">
                <input value="{{ old('Withdrawal') }}" type="number" class="mb-2 form-control  @error('Withdrawal') is-invalid @enderror rounded-bottom" id="Withdrawal" placeholder="Withdrawal" name="Withdrawal">
                <label for="Withdrawal">Withdrawal number</label>

                @error('Withdrawal')
                <div class="invalid-feedback">{{$message }}</div>
                @enderror
            </div>
            <label class="text-white" for="Result" id="resultLabel">Number of gacha point convert to idr: 0</label>
        </div>
    </div>

    <div class="row justify-content-center text-center">
        <div class="col-md-5">

            <button class="btn btn-primary w-30" type="button" id="withdrawButton" data-bs-toggle="modal" data-bs-target="#withdrawalModal">Withdraw</button>

    </div>

    </div>
    <div class="modal fade" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawalModalLabel">Withdrawal Confirmation</h5>
                    <button type="button" class="btn-close" id="closeButton" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <p id="withdrawalAmount"></p>
                    <p id="bankAccount"></p>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton">Cancel</button>
                    <button class="btn btn-primary w-30 " type="submit" id="idnumcardreq">Withdraw</button>
                </div>
            </div>
        </div>
    </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
      $(document).ready(function(){
            $("#cancelButton").click(function(){
                $("#withdrawalModal").modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });
        });
        $(document).ready(function(){
            $("#closeButton").click(function(){
                $("#withdrawalModal").modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            var withdrawalInput = document.getElementById('Withdrawal');
            var resultLabel = document.getElementById('resultLabel');



            withdrawalInput.addEventListener('input', function () {
                var inputValue = withdrawalInput.value;
                var resultValue = inputValue * 150;

                // Perbarui label hasil perkalian
                resultLabel.textContent = 'Number of gacha point convert to idr: ' + (resultValue || 0);
            });

            // Tampilkan modal saat tombol "Withdraw" ditekan
            var withdrawButton = document.getElementById('withdrawButton');
            withdrawButton.addEventListener('click', function () {
                var withdrawalAmount = withdrawalInput.value * 150;
                var bankAccount = "{{ auth()->user()->norekening }}";

                // Set nilai modal sesuai data yang dibutuhkan
                document.getElementById('withdrawalAmount').textContent = 'Total Amount in IDR: RP ' + (withdrawalAmount || 0);
                document.getElementById('bankAccount').textContent = 'To Bank Account: ' + bankAccount;

                // Tampilkan modal
                var withdrawalModal = new bootstrap.Modal(document.getElementById('withdrawalModal'));
                withdrawalModal.show();
            });

        });


    </script>
@endsection
