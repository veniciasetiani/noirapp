@extends('layouts/main')
@section('container')
    {{-- @dd($permissions) --}}
    <style>
        /* Gaya untuk label "available days" */
        .form-check-label {
            display: inline-block;
            padding: 5px 10px;
            margin: 5px;
            background-color: #ffffff;
            /* Ganti dengan warna latar belakang yang diinginkan */
            color: #a20000;
            /* Warna teks untuk kontras */
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <div class="row justify-content-center">
        <p class="text-right" id="registertext">Role Request Status : {{ $status }}</p>

        <div class="col-lg-5">
            @if (session()->has('success'))
                <div class="alert alert-success col-lg-5" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session()->has('danger'))
                <div class="alert alert-danger col-lg-5" role="alert">
                    {{ session('danger') }}
                </div>
            @endif
            <main class="form-registration">
                <h1 class="h3 mb-3 fw-normal text-center" id="registertext">Request Role Form</h1>
                <form action="/role/request" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-floating">
                        <div class="">
                            <label for="gamecategory" class="text-white">Game Category</label>
                        </div>
                        <select class="form-select" id="category" name="category_id">
                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"{{ $permissions->isEmpty() ? '' : ($permissions[0]->category_id == $category->id ? ' selected' : '') }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-floating mt-2">
                        <div class="text-white">
                            <label for="rolecategory">Role</label>
                        </div>
                        <select class="form-select" id="role" name="role_id" onchange="updatePrice()">
                            @foreach ($roles as $role)
                                <option
                                    value="{{ $role->id }}"{{ $permissions->isEmpty() ? '' : ($permissions[0]->role_id == $role->id ? ' selected' : '') }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-floating mt-2">
                        <div class="text-white">
                            <label for="price">Price</label>
                        </div>
                        <input style="border-radius: 5px" value="{{ $permissions->isEmpty() ? old('price', 100) : $permissions[0]->price }}"
                        type="number" class="mb-2 form-control @error('price') is-invalid @enderror" id="price" placeholder="price" name="price" readonly>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mt-2">
                        <div class="text-white">
                            <label for="body">Bio (pengalaman/skill)</label>
                        </div>
                        <input style="border-radius: 5px"
                            value="{{ $permissions->isEmpty() ? old('body') : $permissions[0]->body }}" type="text"
                            class="mb-2 form-control @error('body') is-invalid @enderror" id="body" placeholder="body"
                            name="body">
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-white">
                        <label for="image" class="form-label">Upload Your Profile Image</label><br>
                        {{-- Check if there is data in permission --}}
                        @if (isset($permissions) &&
                                $permissions->isNotEmpty() &&
                                $permissions[0]->statcode === 'APV' &&
                                $permissions[0]->imageprofile)
                            {{-- Display the image from permission if available --}}
                            <img src="{{ asset('storage/' . $permissions[0]->imageprofile) }}"
                                class="img-profile-preview img-fluid mb-3 col-sm-5">
                        @else
                            {{-- Display the image preview if user has filled in the form --}}
                            <img class="img-profile-preview img-fluid mb-3 col-sm-5">
                        @endif
                        <input class="form-control @error('imageprofile') is-invalid @enderror" style="border-radius: 5px"
                            type="file" id="imageprofile" name="imageprofile" onchange="previewImageProfile()">
                        @error('imageprofile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Code for Game Skill Image -->
                    <div class="mb-3 text-white">
                        <label for="image" class="form-label">Upload Your Game Skill Image</label><br>
                        @if (isset($permissions) && $permissions->isNotEmpty() && $permissions[0]->statcode === 'APV' && $permissions[0]->image)
                            <img src="{{ asset('storage/' . $permissions[0]->image) }}"
                                class="img-preview img-fluid mb-3 col-sm-5">
                        @else
                            <img class="img-preview img-fluid mb-3 col-sm-5">
                        @endif
                        <input class="form-control @error('image') is-invalid @enderror" style="border-radius: 5px"
                            type="file" id="image" name="image" onchange="previewImage()">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Code for Game Skill Video -->
                    <div class="mb-3 text-white">
                        <label for="video" class="form-label">Upload Your Game Skill Video</label>
                        @if(isset($permissions) && $permissions->isNotEmpty() && $permissions[0]->statcode === 'APV' && $permissions[0]->video)
                            <div class="video-container">
                                <video class="d-block w-100 video-preview" controls>
                                    <source src="{{ asset('storage/' . $permissions[0]->video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @else
                            <div class="video-container" style="display: none;">
                                <video class="d-block w-100 video-preview" controls>
                                    <!-- Display an empty video element for preview -->
                                </video>
                            </div>
                        @endif
                        <input class="form-control" style="border-radius: 5px"
                            type="file" id="video" name="video" onchange="previewVideo()">
                        @error('video')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>







                    {{-- <div class="mb-3 text-white">
                        <label for="video" class="form-label">Upload Your Game Skill Video</label>
                        @if (isset($permissions) && $permissions->isNotEmpty() && $permissions[0]->statcode === 'APV' && $permissions[0]->video)
                            <video class="d-block w-100 video-preview" controls>
                                <source src="{{ asset('storage/' . $permissions[0]->video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                        <video class="d-block w-100 video-preview" controls>        </video>
                        <!-- Display an empty video element for preview -->
                            </video>
                        @endif
                        <input class="form-control" style="border-radius: 5px" type="file" id="video" name="video"
                            onchange="previewVideo()">
                        @error('video')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div> --}}

                    <div class="form-floating mt-2">
                        {{-- <div class="text-white">
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
                                        <input type="checkbox" name="available_days[{{ $dayName }}]"
                                            value="{{ $dayName }}">
                                    </label>
                                @endfor
                                <div class="form-floating mt-2">
                                    <div class="text-white">
                                        <label for="available_times">Choose Time</label>
                                    </div>
                                    <select name="available_time_start" class="form-select mb-2">
                                        @for ($hour = 0; $hour <= 23; $hour++)
                                            @for ($minute = 0; $minute <= 59; $minute += 15)
                                                <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">
                                                    {{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                                            @endfor
                                        @endfor
                                    </select>
                                    <select name="available_time_end" class="form-select mb-2">
                                        @for ($hour = 0; $hour <= 23; $hour++)
                                            @for ($minute = 0; $minute <= 59; $minute += 15)
                                                <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">
                                                    {{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                                            @endfor
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-floating">
                            <input value="{{ $permissions->isEmpty() ? old('norekening') : $permissions[0]->norekening }}"
                                type="norekening"
                                class="mb-2 form-control @error('norekening') is-invalid @enderror rounded-bottom"
                                id="norekening" placeholder="norekening" name="norekening">
                            <label for="norekening">Nomor Rekening</label>
                            @error('norekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <button class="btn btn-primary w-50" style="margin-top:2%; margin-left: 25%; margin-bottom: 2%" type="submit" id="register">Request now</button>
                </form>
            </main>
        </div>
        {{-- @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button class="btn btn-primary w-50" type="submit" >Request now</button>
                </form>
            </main>
        </div> --}}

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function () {
                // Panggil fungsi updatePrice saat halaman dimuat
                updatePrice();

                // Tambahkan event listener untuk perubahan pada dropdown role
                $('#role').change(function () {
                    // Panggil fungsi updatePrice setiap kali peran dipilih
                    updatePrice();
                });

                // Tambahkan event listener saat halaman sepenuhnya dimuat
                document.addEventListener('DOMContentLoaded', function () {
                    updatePriceValidationMessage();
                });
            });

    function updatePrice() {
        const roleDropdown = $('#role');
        const priceInput = $('#price');

        // Mendapatkan nilai role_id yang dipilih
        const selectedRoleId = roleDropdown.val();

        // Set nilai harga berdasarkan role_id
        if (selectedRoleId === '2') {
            priceInput.val(100);
            priceInput.prop('readonly', true); // Tetapkan sebagai readonly
        } else if (selectedRoleId === '1') {
            // Hanya mengubah nilai jika masih dalam rentang 300-500
            const currentPrice = parseInt(priceInput.val());
            if (isNaN(currentPrice) || currentPrice < 300 || currentPrice > 500) {
                priceInput.val(''); // Kosongkan nilai jika di luar rentang
                priceInput.prop('readonly', false); // Hapus atribut readonly
            }
            priceInput.attr('placeholder', 'Enter price between 300 and 500');
        } else {
            priceInput.val(''); // Kosongkan nilai untuk role lainnya
            priceInput.prop('readonly', false); // Hapus atribut readonly
        }

        // Panggil fungsi untuk memperbarui pesan validasi harga
    }

            function previewImage() {
                const image = document.querySelector('#image');
                const imgPreview = document.querySelector('.img-preview');
                imgPreview.style.display = 'block';
                const fileReader = new FileReader();
                fileReader.readAsDataURL(image.files[0]);
                fileReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            }

            function previewImageProfile() {
                const imageProfile = document.querySelector('#imageprofile');
                const imgProfilePreview = document.querySelector('.img-profile-preview');
                imgProfilePreview.style.display = 'block';
                const fileReader = new FileReader();
                fileReader.readAsDataURL(imageProfile.files[0]);
                fileReader.onload = function(oFREvent) {
                    imgProfilePreview.src = oFREvent.target.result;
                }
            }

            function previewVideo() {
    const video = document.querySelector('#video');
    const videoPreviewContainer = document.querySelector('.video-container');

    console.log('Video Element:', video);  // Log the video element

    if (video && videoPreviewContainer) {
        console.log('Video Preview Container:', videoPreviewContainer);  // Log the video preview container

        videoPreviewContainer.style.display = 'block';

        const fileReader = new FileReader();
        fileReader.readAsDataURL(video.files[0]);
        fileReader.onload = function(event) {
            console.log('FileReader Result:', event.target.result);  // Log the result of FileReader

            const videoPreview = videoPreviewContainer.querySelector('.video-preview');
            if (videoPreview) {
                videoPreview.src = event.target.result;
            } else {
                console.error('Video Preview element not found.');
            }
        }
    } else {
        console.error('Video element or Video Preview container not found.');
    }
}



        </script>
    </div>
@endsection
