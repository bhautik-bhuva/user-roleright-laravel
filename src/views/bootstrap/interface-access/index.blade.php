@extends('laravelMain::' . $layout_file)
@section('title', 'Access Types')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Access Type Management</h4>
            @if (Route::has('useraccess.interface-access.create') && Route::has('useraccess.interface-access.store'))
                <a href="{{ route('useraccess.interface-access.create') }}" class="btn btn-sm btn-primary">Create Access Type</a>
            @endif
        </div>
        <!-- create.blade.php -->
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Allowed Access Types</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accessForItems as $accessFor)
                    <tr>
                        <td>{{ $accessFor->id }}</td>
                        <td>{{ $accessFor->name }}</td>
                        <td>{{ $accessFor->access_type ?: '-' }}</td>
                        <td><a href="{{ route('useraccess.interface-access.edit', $accessFor) }}"
                                class="px-2 btn btn-warning btn-sm me-1"><i class="fa fa-pencil"></i></a>
                            <form class="d-inline" method="POST"
                                action="{{ route('useraccess.interface-access.delete', $accessFor) }}"
                                onsubmit="return confirm('Delete this access type?');">@csrf @method('DELETE')<button
                                    class="delectBtn px-2 btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
@endpush