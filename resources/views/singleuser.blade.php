{{-- BUAT HALAMAN DETAIL POST --}}
@extends('layouts/main')

<head>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
</head>

@section('container')
    <style>
        .carousel-control-prev,
        .carousel-control-next {
            background-color: orange;
        }

        .carousel-controls {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1;
        }

        /* .carousel-control-prev {
                        left: 1em;
                        margin-left: 325px;
                    }

                    .carousel-control-next {
                        right: 1em;
                        margin-right: 350px;

                    } */

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 30px;
            height: 30px;
            background-size: 100%;
            filter: invert(1);
        }

        .carousel-item img,
        .carousel-item video {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .filled-star {
            font-size: 24px;
            /* Ukuran bintang yang diisi */
            color: yellow;
            /* Warna bintang saat diisi */
        }

        .empty-star {
            font-size: 24px;
            /* Ukuran bintang kosong */
            color: gray;
            /* Warna bintang kosong */
        }
    </style>

    <div class="container-lg mt-3 d-flex flex-column justify-content-center align-items-center">
        <div class="w-100">

        <h1 class="h2-title-text mb-4">USER PROFILE</h1>
        <hr>
        </div>
        <div class="row justify-content-center mt-2 mb-5 w-100">

            <div class="col-md-4 d-inline-block justify-content-right">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @if (!$permissions)
                            <!-- Display content based on permissions -->
                                    <div class="carousel-item active">
                                        <img src="{{ asset('storage/' . $permissions->image) }}" alt="Uploaded Image"
                                            class="d-block w-100">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $permissions->imageprofile) }}"
                                            alt="Uploaded Image" class="d-block w-100">
                                    </div>
                                    <div class="carousel-item">
                                        <video class="d-block w-100" controls>
                                            <source src="{{ asset('storage/' . $permissions->video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                        @else
                            <!-- Display default image when both permissions and updateSingleBlade data are empty -->
                            <div class="carousel-item active">
                                <img src="https://source.unsplash.com/800x400/?game" class="d-block w-100"
                                    alt="Default Image">
                            </div>
                        @endif
                    </div>
                    <div class="carousel-controls">
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="d-flex align-items-start mb-3 card bg-dark py-2 mt-3 mt-md-0 px-4">
                    <h2 class="m-0">{{ $user->username }}</h2>

                    <div>
    
                        @php
                            $roundedRating = floor($averageRating * 2) / 2; // Bulatkan ke setengah bintang terdekat ke bawah
                            $fullStars = floor($roundedRating); // Bintang penuh
                            $hasHalfStar = $roundedRating - $fullStars === 0.5; // Cek apakah terdapat setengah bintang
                            $username = $user->username;
                        @endphp
        
                        @for ($i = 0; $i < $fullStars; $i++)
                            <span><i class="bi bi-star-fill filled-star"
                                    style="color: yellow; font-size: 1.2rem;"></i></span>
                        @endfor
        
                        @if ($hasHalfStar)
                            <i class="bi bi-star-half filled-star" style="color: yellow; font-size: 1.2rem;"></i>
                        @endif
        
                        @for ($i = 0; $i < 5 - ceil($roundedRating); $i++)
                            <span><i class="bi bi-star empty-star" style=" font-size: 1.2rem;"></i></span>
                        @endfor
                    </div>
                </div>
                 
                <div class="row">
                    <div class="col-12 col-md-6 pe-md-1 mb-2 mb-md-0">
                        <div class="card p-3 bg-transparent border-0 py-2 mb-2">
                            <h4 class="user-desc-title mb-1">
                                Skill</h4>
                            <hr class="m-0 mb-2">

                            <div class="row">
                                <div class="col-md-5">
                                    @php
                                        $imageSrc = $user->category->image ? asset('storage/' . $user->category->image) : 'https://source.unsplash.com/40x50/?game';
                                    @endphp
                                    @if ($user->category->image)
                                        <img style="border-radius: 10px" src="{{ $imageSrc }}" class="card-img-top"
                                            alt="...">
                                    @else
                                        <img style="border-radius: 10px" src="https://source.unsplash.com/40x50/?game"
                                            class="card-img-top" alt="...">
                                    @endif
                                </div>
                                <div class="col-md-6 text-white mt-5">
                                    {{ $user->category->name }}
                                    <div class="price mt-1"><img src="/img/gatcha.png" style="height:1.25rem" alt="" class="me-2" /> {{ $user->price }} / match</div>
                                </div>
                            </div>
                        </div>
                        <div class="card p-3 bg-transparent border-0 py-2">

                            <h4 class="user-desc-title mb-1">
                                Bio</h4>
                            <hr class="m-0 mb-2">
                            <div class="informasi px-3 py-2">
                                @if ($user->updateSingleBlade && $user->updateSingleBlade->approved)
                                    <p>{{ $user->updateSingleBlade->updated_bio }}</p>
                                @else
                                    <p>{{ $user->body }}</p>
                                @endif
                            </div>


                            @if (isset($image) && isset($video))
                                <!-- Display image and video for role request -->
                                <img src="{{ asset('storage/' . $image) }}" alt="Image">
                                <video controls>
                                    <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @elseif(isset($bio) && isset($image) && isset($video))
                                <!-- Display updated data for update request -->
                                <p>Bio: {{ $bio }}</p>
                                <img src="{{ asset('storage/' . $image) }}" alt="Image">
                                <video controls>
                                    <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-6 ps-md-1">
                        <div class="card p-3 bg-transparent border-0 py-2 mb-2">
                            <h4 class="user-desc-title mb-1">
                                Available Times</h4>
                            <hr class="m-0 mb-2">
                            <div class="informasi px-3 py-2">
                                <ul class="mb-0">
                                    @foreach ($availableTimes as $availableTime)
                                        @php
                                            $startTime = strtotime($availableTime->start_time);
                                            $endTime = strtotime($availableTime->end_time);
                                            $day = $availableTime->day;
                                        @endphp

                                        <li>
                                            {{ date('H:i', $startTime) }} - {{ date('H:i', $endTime) }}
                                            ({{ date('l', strtotime($day)) }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="card p-3 bg-transparent border-0 py-2 mb-4">
                            <h4 class="user-desc-title mb-1">
                                Actions</h4>
                            <hr class="m-0 mb-2">
                            <div class="row gx-2 align-items-center justify-content-center">
                                <a href="/addtocart/{{ $user->username }}" class="btn col m-2">Order</a>
                                <a href="/report/{{ $user->username }}" class="btn col m-2">Report</a>
                                
                                <p class="col-auto text-center align-center text-white mb-0">or</p>
    
                                <button type="submit" class="btn-chat p-2 py-1 col  m-2" data-chat-with="{{ $user->id }}"
                                    onclick="window.location.href='/chatify/{{ $user->id }}'">
                                    <i class="bi bi-chat-heart"></i> Chat
                                </button>
                            </div>
      
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <h2 class="user-desc-title mb-2">
                    Rating</h2>
                <hr class="m-0 mb-2">
                @include('rating/detail')
            </div>
        </div>
    </div>



    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('scheduleForm');
            form.addEventListener('submit', function(event) {
                const dateInput = document.getElementById('date');
                const selectedDate = new Date(dateInput.value);

                const availableDays = [@foreach ($availableTimes as $availableTime) '{{ date("D", strtotime($availableTime->day)) }}', @endforeach];

                const selectedDay = selectedDate.toLocaleDateString('en-US', { weekday: 'short' });

                if (!availableDays.includes(selectedDay)) {
                    event.preventDefault();
                    alert('Tanggal tidak tersedia. Silakan pilih tanggal lain.');
                }
            });
        });
        @if (session('error'))
        // Show a JavaScript alert with the error message
        alert('{{ session('error') }}');
        @endif
    </script> --}}
@endsection
