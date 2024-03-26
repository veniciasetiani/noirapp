@extends('layouts/main')
@section('container')

    {{-- @dd($users[0]->permissions) --}}
    @if ($users->count())
        <div class="container">
            <div class="card mb-3 text-center my-3 user-card p-0">
                <form action="/filter" method="GET">
                    <div class="row pb-3">
                        <div class="col-md-5 pt-4">

                        </div>

                        <div class="col-md-3">
                            <label for="">Player/coach</label>
                            <select class="form-select" aria-label="Default select example" name="role">
                                <option selected>role</option>
                                <option value="2">Player</option>
                                <option value="1">Coach</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="">Available day :</label>
                            <select class="form-select" aria-label="Default select example" name="day">
                                <option selected>Available day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>


                        <div class="col-md-3">
                            <label for="">Start time :</label>
                            <input type="date" class="form-control" name="startdate">
                        </div>


                        <div class="col-md-3">
                            <label for="">End time :</label>
                            <input type="date" class="form-control" name="enddate">
                        </div>

                        <input type="text" class="form-control" name="category" value="{{ $users[0]->category->name }}"
                            hidden>

                        <div class="col-md-1 pt-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>

                @if (!empty($users) && isset($users[0]) && $users[0]->imageprofile)
                    <div style="max-height:350px; overflow:hidden;">
                        <img src="{{ asset('storage/' . $users[0]->imageprofile) }}" class="img-fluid"
                            alt="{{ $users[0]->category ? $users[0]->category->name : '' }}">
                    </div>
                @else
                    @php
                        $firstPermission = $users[0]->permissions->first();
                    @endphp
                    @if ($firstPermission && $firstPermission->statcode === 'APV')
                        <img src="{{ asset('storage/' . $firstPermission->imageprofile) }}" alt="Uploaded Image">
                    @else
                        <img src="https://source.unsplash.com/1200x400/?{{ $users[0]->category ? $users[0]->category->name : '' }}"
                            class="card-img-top" alt="...">
                    @endif
                @endif

                <div class="card-body position-relative">
                    <h4 class="user-card-text m-0">{{ $users[0]->username }}
                        <p class="user-card-text user-card-descr fs-6">
                            {{ $users[0]->created_at->diffForHumans() }}
                        </p>
                    </h4>
                    <p class="user-card-text btn fs-6 position-absolute top-0 end-0 m-3 text-uppercase">
                        @if ($users[0]->category)
                            <a class="text-decoration-none text-white"
                                href="/categories/{{ $users[0]->category->slug }}">{{ $users[0]->category->name }}</a>
                        @else
                            No category
                        @endif
                    </p>

                    <p class="card-text user-card-text">{{ $users[0]->excerpt }}</p>
                    <a class="text-decoration-none btn btn-primary w-100" href="/user/{{ $users[0]->username }}">
                        Read More
                    </a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                @foreach ($users->skip(1) as $user)
                    <div class="col-md-4 mb-3">
                        <div class="card user-card overflow-hidden d-flex flex-column justify-content-between"
                            style="min-width: 15rem; height: 45vh; overflow: hidden;">
                            <div class="position-absolute px-4 py-2 text-white m-2 top-0"
                                style="background-color: rgba(113, 43, 137, 1); right: 0px; border-radius: 5px;">
                                <a class="text-decoration-none text-white"
                                    href="/categories/{{ $user->category ? $user->category->slug : '' }}">{{ $user->role->name }}</a>
                            </div>

                            <div style="height:60%;" class="overflow-hidden bg-secondary d-flex align-items-center">
                                @if ($user->imageprofile)
                                    <img src="{{ asset('storage/' . $user->imageprofile) }}" class="img-fluid"
                                        alt="{{ $user->category ? $user->category->name : '' }}">
                                @else
                                    @php
                                        $firstPermission = $user->permissions->first();
                                    @endphp
                                    @if ($firstPermission && $firstPermission->statcode === 'APV')
                                        <img src="{{ asset('storage/' . $firstPermission->imageprofile) }}"
                                            alt="Uploaded Image" class="object-fit-scale" style="max-width:100%">
                                    @else
                                        <img src="https://source.unsplash.com/500x400?{{ $user->category ? $user->category->name : '' }}"
                                            class="card-img-top object-fit-contain"
                                            alt="{{ $user->category ? $user->category->name : '' }}">
                                    @endif
                                @endif
                            </div>

                            <div class="card-body"
                                style="flex: 1; /* Ensures the card-body takes up the available vertical space */">
                                <div class="row">
                                    <h5 class="col-12 col-lg-8 user-card-text m-0">
                                        {{ $user->username }}
                                        <p class="user-card-text user-card-descr mb-2 crt-at-small-text">
                                            {{ $user->created_at->diffForHumans() }}
                                        </p>
                                    </h5>
                                    <span class="col-12 col-lg-4 d-flex justify-content-lg-end">
                                        <p
                                            class="user-card-text btn fs-6 text-uppercase btn-secondary d-flex align-items-center">
                                            <a class="text-decoration-none text-white text-wrap"
                                                href="/categories/{{ $user->category ? $user->category->slug : '' }}">
                                                {{ $user->category ? $user->category->name : '' }}</a>
                                        </p>
                                    </span>
                                </div>
                                <p class="card-text user-card-text">{{ $user->excerpt }}</p>
                                <a class="text-decoration-none btn btn-primary w-100" href="/user/{{ $user->username }}">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p class="text-center fs-4 text-white">no user found</p>
    @endif

@endsection
