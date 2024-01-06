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

        .carousel-control-prev {
            left: 1em;
            margin-left: 325px;
        }

        .carousel-control-next {
            right: 1em;
            margin-right: 350px;

        }

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
            font-size: 24px; /* Ukuran bintang yang diisi */
            color: yellow; /* Warna bintang saat diisi */
        }

        .empty-star {
            font-size: 24px; /* Ukuran bintang kosong */
            color: gray; /* Warna bintang kosong */
        }
    </style>

    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-4 d-inline-block justify-content-right">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @if (
                            !empty($user->updateSingleBlade->image_path) &&
                                !empty($user->updateSingleBlade->video_path) &&
                                $user->updateSingleBlade->is_approved === 1)
                            <!-- Display content when both image_path and video_path are not empty and is_approved is 1 -->
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $user->updateSingleBlade->image_path) }}"
                                    class="d-block w-100" alt="Profile Image">
                            </div>
                            <div class="carousel-item">
                                <video class="d-block w-100" controls>
                                    <source src="{{ asset('storage/' . $user->updateSingleBlade->video_path) }}"
                                        type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @elseif (!$permissions->isEmpty())
                            <!-- Display content based on permissions -->
                            @foreach ($permissions as $permission)
                                @if ($permission->statcode === 'APV')
                                    <div class="carousel-item active">
                                        <img src="{{ asset('storage/' . $permission->image) }}" alt="Uploaded Image"
                                            class="d-block w-100">
                                    </div>
                                    @if (property_exists($permission, 'imageprofile'))
                                        <div class="carousel-item">
                                            <img src="{{ asset('storage/' . $permission->imageprofile) }}"
                                                alt="Uploaded Image" class="d-block w-100">
                                        </div>
                                    @endif
                                    <div class="carousel-item">
                                        <video class="d-block w-100" controls>
                                            <source src="{{ asset('storage/' . $permission->video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @endif
                            @endforeach
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

                <h2 class="mb-3 mt-2">{{ $user->username }}</h2>
                <div class="col-md-4 d-inline">
                    <a href="/addtocart/{{ $user->username }}" class="btn btn-lg">Order</a>
                    <button type="submit" class="btn-chat" data-chat-with="{{ $user->id }}"
                        onclick="window.location.href='/chatify/{{ $user->id }}'"><i class="bi bi-chat-heart"></i> Chat</button>
                        <a href="/report/{{ $user->username }}" class="btn btn-lg">Report</a>
                </div>
            </div>

            <div class="col-md-3">
                <p
                    style="font-size: 2rem; color:orange; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
                    Skill</p>
                <hr>

                <div class="row">
                    <div class="col-md-5">
                        @php
                            $imageSrc = $user->category->image ? asset('storage/' . $user->category->image) : 'https://source.unsplash.com/40x50/?game';
                        @endphp
                        @if ($user->category->image)
                            <img style="border-radius: 10px" src="{{ $imageSrc }}" class="card-img-top" alt="...">
                        @else
                            <img style="border-radius: 10px" src="https://source.unsplash.com/40x50/?game"
                                class="card-img-top" alt="...">
                        @endif
                    </div>
                    <div class="col-md-6 text-white mt-5">
                        {{ $user->category->name }}
                        <div class="price mt-1"><i class="bi bi-coin"></i> {{ $user->price }} / match</div>
                    </div>
                </div>


                <p class="mt-4"
                    style="font-size: 2rem; color:orange; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
                    Bio</p>
                <hr>
                <div class="informasi">
                    @if ($user->updateSingleBlade && $user->updateSingleBlade->approved)
                        <p>{{ $user->updateSingleBlade->updated_bio }}</p>
                    @else
                        <p>{{ $user->body }}</p>
                    @endif
                </div>
                <p class="mt-4"
                    style="font-size: 2rem; color:orange; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
                    Available Times</p>
                <hr>
                <div class="informasi">
                    <ul>
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

                <p class="mt-4"
                style="font-size: 2rem; color:orange; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
                Rating</p>
                <hr>
                <div>

                    @php
                        $roundedRating = floor($averageRating * 2) / 2; // Bulatkan ke setengah bintang terdekat ke bawah
                        $fullStars = floor($roundedRating); // Bintang penuh
                        $hasHalfStar = $roundedRating - $fullStars === 0.5; // Cek apakah terdapat setengah bintang
                        $username = $user->username;
                    @endphp

                    @for ($i = 0; $i < $fullStars; $i++)
                        <a href="/rating-detail/{{ $username }}"><i class="bi bi-star-fill filled-star" style="color: yellow;"></i></a>
                    @endfor

                    @if ($hasHalfStar)
                        <i class="bi bi-star-half filled-star" style="color: yellow;"></i>
                    @endif

                    @for ($i = 0; $i < 5 - ceil($roundedRating); $i++)
                        <a href="/rating-detail/{{ $username }}"><i class="bi bi-star empty-star"></i></a>
                    @endfor
                </div>
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
