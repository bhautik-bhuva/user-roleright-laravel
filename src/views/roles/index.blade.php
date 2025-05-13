@extends('laravelMain::contentNavbarLayout')
@section('title', 'Roles List')

@section('content')
<h1>
    Role Listing
</h1>

<!-- create.blade.php -->
<a href="{{ route('.role.add') }}" class="btn btn-primary">Create Role</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($roles as $role)
            <tr>
                <td>{{ $role['id'] }}</td>
                <td>{{ $role['name'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#rolesTable').DataTable({
            // Add any DataTable options here
        });
    });
</script>
@endpush