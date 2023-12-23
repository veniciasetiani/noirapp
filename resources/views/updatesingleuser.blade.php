@extends('layouts/main')

@section('container')
    <div class="container">
        @if (session()->has('success'))
        <div class="alert alert-success col-lg-5" role="alert">
            {{ session('success') }}
        </div>
    @endif
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default-color">
                    <div class="card-body">
                        <h2 class="card-title">Update Profile</h2>

                        <form action="/updatesingleuser" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3 form-group">
                                <label for="bio" class="form-label">Bio:</label>
                                <textarea class="form-control" name="bio"></textarea>
                            </div>

                            <div class="mb-3 form-group">
                                <label for="image" class="form-label">Skill Dsiplay Image:</label>
                                <input type="file" class="form-control" name="image">
                            </div>

                            <div class="mb-3 form-group">
                                <label for="video" class="form-label"> Skill Display Video:</label>
                                <input type="file" class="form-control" name="video">
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
