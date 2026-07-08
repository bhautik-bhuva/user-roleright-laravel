@extends('laravelMain::' . $layout_file)
@section('title', 'Create Menu')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->
@endpush
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Create User</h4>
        @if (Route::has('useraccess.user.list'))
            <a class="btn btn-light btn-sm border" href="{{ route('useraccess.user.list') }}">Back</a>
        @endif
    </div>
    @if (Route::has('useraccess.user.store'))
        <div class="card">
            <div class="card-body">
                <form action="{{ route('useraccess.user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="form-group col-lg-6">
                            <label for="name" class="text-black">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="method" class="text-black">Email Address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-lg-6">
                            <label for="action" class="text-black">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="menu_status" class="text-black">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" >
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-lg-6">
                            <label for="module_label" class="text-black">Select Role</label>
                            <select class="form-control form-select @error('role') is-invalid @enderror" id="role" name="role">
                                <option value="" <?php echo (old('role') == "") ? 'selected' : ''; ?>>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role['id'] }}" <?php echo (old('role') == $role['id']) ? 'selected' : ''; ?>>{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>                        
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger" role="alert">You do not have permission to create an user.</div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script> -->
