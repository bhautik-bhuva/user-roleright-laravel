@extends('laravelMain::' . $layout_file)
@section('title', 'Edit Access Type')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Access Type</h4>
        @if (Route::has('useraccess.interface-access.list'))
            <a class="btn btn-sm btn-light border" href="{{ route('useraccess.interface-access.list') }}">Back</a>
        @endif
    </div>
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if (Route::has('useraccess.interface-access.update'))
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('useraccess.interface-access.update', $accessFor) }}">
                    @csrf 
                    @method('PUT') 
                    @include('useraccess::bootstrap.interface-access._form')
                    <button class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger" role="alert">You do not have permission to edit this access type.</div>
    @endif
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
@endpush