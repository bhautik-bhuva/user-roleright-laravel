@extends('laravelMain::' . $layout_file)
@section('title', 'Menu List')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Menu Management</h4>
            @if (Route::has('useraccess.menu.create') && Route::has('useraccess.menu.store'))
            <a href="{{ route('useraccess.menu.create') }}" class="btn btn-sm btn-primary">Create Menu</a>
            @endif
        </div>
        <!-- create.blade.php -->
        @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-striped" id="menuTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action URL</th>
                    <th>Route Name</th>
                    <th>Prefix</th>
                    <th>Contoller Name</th>
                    <th>Method</th>
                    <th>Route Type</th>
                    <th>Menu Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menus as $menu) { ?>
                    <tr>
                        <td><?php echo $menu['id']; ?></td>
                        <td><?php echo $menu['name']; ?></td>
                        <td width="150px"><?php echo $menu['action']; ?></td>
                        <td><?php echo json_decode($menu['extra_options'], true)['route_name']; ?></td>
                        <td><?php echo json_decode($menu['extra_options'], true)['prefix']; ?></td>
                        <td><?php echo $menu['controller']; ?></td>
                        <td><?php echo $menu['method']; ?></td>
                        <td><?php echo $menu['route_type']; ?></td>
                        <td width="100px"><?php echo implode(',', $menu['access_types']); ?></td>
                        <td><?php echo ($menu['status'] == 1 || $menu['status'] == "1") ? '<span class="badge bg-success text-white"> Active </span>' : '<span class="badge bg-danger text-white"> Deactive </span>'; ?></td>
                        <td width="80px">
                            @if (Route::has('useraccess.menu.edit') && Route::has('useraccess.menu.update'))
                            <a href="<?php echo route("useraccess.menu.edit", $menu['id']) ?>" class="editBtn px-2 btn btn-warning btn-sm me-1"><i class="fa fa-pencil"></i></a>
                            @endif
                            @if (Route::has('useraccess.menu.delete'))
                            <form action="<?php echo route('useraccess.menu.delete', $menu['id']); ?>" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="delectBtn px-2 btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
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
        $('#menuTable').DataTable({
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    orderable: true,
                    className: 'reorder',
                    targets: 0
                },
                {
                    orderable: false,
                    targets: '_all'
                }
            ],
            language: {
                lengthMenu: "_MENU_ Rows per page"
            },
            lengthMenu: [[ 10, 20, 50], [ 10, 20, 50]],
        });
    });
</script>
@endpush
