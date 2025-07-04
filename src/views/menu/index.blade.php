@extends('layouts.app')
@section('title', 'Menu List - Laravel')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Menu Management</h4>
        <a href="{{ route('menu.create') }}" class="btn btn-primary">Create Menu</a>
    </div>
    <!-- create.blade.php -->
    <table class="table table-striped" id="menuTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu) { ?>
                <tr>
                    <td><?php echo $menu['id']; ?></td>
                    <td><?php echo $menu['name']; ?></td>
                    <td><?php echo $menu['name']; ?></td>
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
        // Initialize DataTable
        $('#menuTable').DataTable( );
    });
</script>
@endpush
