@extends('layouts/main')
@section('container')
<div class="row justify-content-center">
    <div class="col-lg-5">
    <main class="form-registration">
        
      @if(session()->has('success'))
        <div class="alert alert-success col-lg-5" role="alert">
            {{ session('success') }}
        </div>
      @endif

      <h1 class="h3 mb-3 fw-normal text-center" id="registertext">Request Role Form</h1>
        <form action="/role/request" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-floating">
            <div class="">
                <label for="gamecategory" class="text-white">Game Category</label>
            </div>
                <select class="form-select" id="category" name="category_id" >
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? ' selected' : ' ' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
          </div>

          <div class="form-floating mt-2">
            <div class="text-white">
                <label for="rolecategory">Role</label>
            </div>
            <select class="form-select" id="role" name="role_id" >
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? ' selected' : ' ' }}>{{ $role->name }}</option>
                @endforeach
            </select>
          </div>

          <div class="form-floating mt-2">
            <div class="text-white">
                <label for="price">Price</label>
            </div>
            <input style="border-radius: 5px"  value="{{ old('price',100) }}" type="number" class="mb-2 form-control @error('price') is-invalid @enderror" id="price" placeholder="price" name="price">
  
            @error('price')
            <div class="invalid-feedback">{{$message }}</div>
            @enderror
          </div>

          <div class="mb-3 text-white">
            <label for="image" class="form-label">Upload Your Game Skill Image</label>
            {{-- ini bawah, biar bisa preview image --}}
            <img class="img-preview img-fluid mb-3 col-sm-5">
            <input class="form-control @error('image') is-invalid @enderror" style="border-radius: 5px" type="file" id="image" name="image" onchange="previewImage()">

            @error('image')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        </div>
          
          <button class="btn btn-primary w-50" type="submit" id="register">Request now</button>
        </form>
  
    </main>
    </div>

    <script>
        function previewImage(){
            const image = document.querySelector('#image');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';
            const ofReader = new FileReader();
            ofReader.readAsDataURL(image.files[0]);

            ofReader.onload = function(oFREvent ){
                imgPreview.src = oFREvent.target.result;
            }
            }
    </script>

  </div>
@endsection