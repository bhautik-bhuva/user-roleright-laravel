
@extends('laravelMain::' . $layout_file)
@section('title', 'Roles List')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Role Management</h4>
        @if (Route::has('useraccess.role.create') && Route::has('useraccess.role.store'))
            <a href="{{ route('useraccess.role.create') }}" class="btn btn-sm btn-primary">Create Role</a>
        @endif
    </div>
    <!-- create.blade.php -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    <table class="table table-striped" id="rolesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Access For</th>
                <th>Access Permission</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roles as $role) { ?>
                <tr>
                    <td><?php echo $role['id']; ?> </td>
                    <td><?php echo $role['name']; ?></td>
                    <td><?php echo implode(", ",$role['interface_access']); ?></td>
                    <td><?php echo $role['access']; ?></td>

                    <td>
                        @if (Route::has('useraccess.role.edit') && Route::has('useraccess.role.update'))
                            <a href="<?= route("useraccess.role.edit", $role['id']) ?>" class="editBtn mx-1 btn btn-warning btn-sm px-2"><i class="fa fa-pencil"></i></a>
                        @endif
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#rolesTable ').DataTable( {
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: true, className: 'reorder', targets: 0 },
                { orderable: false, targets: '_all' }
            ],
            language: {
                lengthMenu: "_MENU_ Rows per page"
            },
            lengthMenu: [[5, 10, 20, 50], [5, 10, 20, 50]],
        });
    });
</script>
@endpush
