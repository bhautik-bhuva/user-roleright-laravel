@extends('laravelMain::' . $layout_file)
@section('title', 'Roles List')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.tailwindcss.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .dt-container .dt-search input {
        border-radius: 0.375rem;
        border-color: #d1d5db;
        padding: 0.25rem 0.75rem;
    }
    .dt-container .dt-length select {
        border-radius: 0.375rem;
        border-color: #d1d5db;
        padding: 0.25rem 1.5rem 0.25rem 0.5rem;
    }
</style>
@endpush
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Role Management</h4>
        @if (Route::has('useraccess.role.create') && Route::has('useraccess.role.store'))
            <a href="{{ route('useraccess.role.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition">Create Role</a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md" role="alert">{{ session('success') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 display" id="rolesTable">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Access For</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Access Permission</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
                    <?php foreach ($roles as $role) { ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo $role['id']; ?> </td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100"><?php echo $role['name']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo $role['access_for']['name']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap uppercase text-xs font-semibold"><?php echo $role['access']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if (Route::has('useraccess.role.edit') && Route::has('useraccess.role.update'))
                                    <a href="<?= route("useraccess.role.edit", $role['id']) ?>" class="p-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded transition text-xs flex items-center justify-center w-8 h-8"><i class="fa fa-pencil"></i></a>
                                @endif
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>

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
