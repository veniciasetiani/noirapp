{{-- @extends('layouts/main')

@section('container') --}}
<style>
    .rating-details {
        max-width: 600px;
        margin: 0 auto;
    }

    .rating-card {
        /* border: 1px solid #ccc; */
        border-radius: 8px;
        background-color: #f9f9f9;
        position: relative;
    }

    .user-name {
        font-weight: bold;  
    }

    .comment {
        color: #555;
    }
</style>
<div class="row g-2 gy-0 p-0 py-md-2 px-0">
    {{-- <h3 class="title text-white text-center">Ratings detail</h3> --}}
    @foreach($ratings as $rating)
    <div class="col-12 col-md-4">
        <div class="rating-card bg-dark position-relative py-3 px-3">
            <div class="user-name text-white">{{ $rating->buyer->name }}</div>
            <div class="comment mb-2">{{ $rating->comment }}</div>
            <p class="position-absolute top-0 end-0 text-white p-3 opacity-25">{{ $rating->created_at }}</p>
            @for ($i = 0; $i < $rating->rating; $i++)
            <i class="bi bi-star-fill filled-star" style="color: yellow; font-size: 1.25rem;"></i>
            @endfor
        </div>
    </div>
    @endforeach
    <div class="mt-3 ">
        {{ $ratings->links('pagination::bootstrap-5')}}
    </div>

</div>

{{-- @endsection --}}
