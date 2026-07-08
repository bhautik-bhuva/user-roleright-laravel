
@extends('laravelMain::' . $layout_file)
@section('title', 'Users List')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">User and Right Management</h4>
        @if (Route::has('useraccess.user.create') && Route::has('useraccess.user.store'))
            <a href="{{ route('useraccess.user.create') }}" class="btn btn-sm btn-primary">Create User</a>
        @endif
    </div>
    <!-- create.blade.php -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    <table class="table table-striped" id="usersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo $user['id']; ?> </td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        @if (Route::has('useraccess.user.edit') && Route::has('useraccess.user.update'))
                            <a href="<?= route("useraccess.user.edit", $user['id']) ?>" class="editBtn px-2 btn btn-warning btn-sm me-1"><i class="fa fa-pencil"></i></a>
                        @endif
                        @if (Route::has('useraccess.user.delete'))
                            <form action="{{ route('useraccess.user.delete', $user['id']) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="editBtn px-2 btn btn-danger btn-sm me-1"><i class="fa fa-trash"></i></button>
                                <!-- <a href="<?= route("useraccess.user.delete", $user['id']) ?>" class="editBtn mx-1 btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> -->
                            </form>
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
        $('#usersTable ').DataTable( {
            order: [[0, 'desc']],
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
