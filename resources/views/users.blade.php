@extends('layouts/main')
@section('container')

{{-- @dd($category) --}}
@if ($users->count())
    <div class="card mb-3 text-center">

        @if ($users[0]->image)
            <div style="max-height:350px; overflow:hidden;">
                <img src="{{ asset('storage/' . $users[0]->image) }}" class="img-fluid" alt="{{ $users[0]->category ? $users[0]->category->name : '' }}">
            </div>
        @else
            <img src="https://source.unsplash.com/1200x400/?{{ $users[0]->category ? $users[0]->category->name : '' }}" class="card-img-top" alt="...">
        @endif

        <div class="card-body">
            <p>
                <h3>{{ $users[0]->username }}</h3>
                Divisi
                @if ($users[0]->category)
                    <a class="text-decoration-none" href="/categories/{{ $users[0]->category->slug }}">{{ $users[0]->category->name }}</a>
                @else
                    No category
                @endif
                {{ $users[0]->created_at->diffForHumans() }}
            </p>
            <p class="card-text">{{ $users[0]->excerpt }}</p>
            <a class="text-decoration-none btn btn-primary" href="/user/{{ $users[0]->username }}">
                read more..
            </a>
        </div>
    </div>

    <div class="container">
        <div class="row">
            @foreach ($users->skip(1) as $user)
                <div class="col-md-4 mb-3">
                    <div class="card" style="width: 25rem;">
                        <div class="position-absolute px-3 py-2 text-white" style="background-color: rgba(217, 56, 56, 0.5); border-radius: 5px"><a class="text-decoration-none text-white" href="/categories/{{ $user->category ? $user->category->slug : '' }}">{{ $user->category ? $user->category->name : '' }}</a></div>
                        <div class="position-absolute px-4 py-2 text-white" style="background-color: rgba(113, 43, 137, 0.5); right: 0px; border-radius: 5px;"><a class="text-decoration-none text-white" href="/categories/{{ $user->category ? $user->category->slug : '' }}">{{ $user->role->name }}</a></div>

                        @if ($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" class="img-fluid" alt="{{ $user->category ? $user->category->name : '' }}">
                        @else
                            <img src="https://source.unsplash.com/500x400?{{ $user->category ? $user->category->name : '' }}" class="card-img-top" alt="{{ $user->category ? $user->category->name : '' }}">
                        @endif
                        <div class="card-body">
                            <h3>{{ $user->username }}</h3>
                            {{ $user->created_at->diffForHumans() }}

                            <p>{{ $user['excerpt'] }}</p>

                            <a class="text-decoration-none btn btn-primary" href="/user/{{ $user->username }}">
                                read more..
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




{{-- @foreach ($posts->skip(1) as $post)

<article class="mb-5 border-bottom">
    <h2>
        <a class="text-decoration-none" href="/posts/{{ $post->slug }}">
            {{ $post['title'] }}
        </a>
    </h2>
    By <a class="text-decoration-none" href="/authors/{{ $post -> author -> username }}"> {{  $post -> author -> name }}
        in
        <a class="text-decoration-none" href="/categories/{{ $post -> category -> slug}}"> {{  $post -> category-> name  }} </a></p>

    <p>{{ $post['excerpt'] }}</p>

    <a class="text-decoration-none" href="/posts/{{ $post->slug }}">
       read more..
    </a>
</article>


@endforeach --}}
