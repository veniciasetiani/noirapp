@extends('admin.layouts.main')
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
@section('container')
<h1 class="h2">REPORT</h1>

@if(session()->has('success'))
  <div class="alert alert-success col-lg-5" role="alert">
      {{ session('success') }}
  </div>
@endif

<div class="table-responsive small col-lg-5">
  <table class="table table-striped table-sm ">
    <thead>
      <tr class="text-center">
        <th scope="col">#</th>
        <th scope="col">Username</th>
        <th scope="col">Name</th>
        <th scope="col">Report Times</th>
        <th scope="col">Banned Count</th>
        <th scope="col">Detail</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>

    @foreach($users as $user)
      <tr class="text-center">
        <td>{{ $loop ->iteration }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->report_times }}</td>
        <td>{{ $user->unban_times }}</td>
        <td>
            <a href="/report-detail/{{ $user->username }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Detail</a>
        </td>
        <td>
            <form action="{{ $user->ban_status ? route('report.unban', $user->id) : route('report.ban', $user->id) }}" method="POST">
                @csrf
                @if($user->ban_status)
                    @method('POST') <!-- Assuming you use PUT/PATCH method for updating -->
                    <button type="submit" class="btn btn-danger">Unban</button>
                @else
                    <button type="submit" class="btn btn-success">Ban</button>
                @endif
            </form>

        </td>
      </tr>
    </tbody>

    @endforeach
  </table>
</div>
<h1 class="h2">REFUND</h1>
<div class="table-responsive small col-lg-5">
    <table class="table table-striped table-sm ">
      <thead>
        <tr class="text-center">
          <th scope="col">#</th>
          <th scope="col">Reporter(s)</th>
          <th scope="col">Reason</th>
          <th scope="col">Details</th>
          <th scope="col">Photo</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>

      @foreach($scammingReports as $report)
        <tr class="text-center">
          <td>{{ $loop ->iteration }}</td>
          <td>{{ $report->buyer->username }}</td>
          <td>{{ $report->header }}</td>
          <td>{{ $report->detail }}</td>
          <td>
              <button type="button" class="badge bg-info showbtn" data-toggle="modal" data-target="#imageModal{{ $report->id }}"><span class="bi bi-eye " style="color: black"></span></button>
          </td>
          <td>
            <form action="{{ route('accept.report', $report->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success"><i class="bi bi-check2"
                    style="color: black"></i></button>
            </form>
            <form action="{{ route('reject.report', $report->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger"><i class="bi bi-x"
                    style="color: white"></i></button>
            </form>
        </td>

        </tr>
      </tbody>
        </div>
        <div class="modal fade" id="imageModal{{ $report->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $report->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="imageModalLabel{{ $report->id }}">Image Preview</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center">
                  <img src="{{ asset('storage/' . $report->image) }}" alt="Uploaded Image" style="max-width: 100%;">
                </div>
              </div>
            </div>
          </div>
      @endforeach
    </table>
  </div>
</div>



@endsection

