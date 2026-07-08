@extends('laravelMain::' . $layout_file)
@section('title', 'Menu List')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.tailwindcss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Tailwind compatible styles for DataTables wrapper -->
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
        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Menu Management</h4>
        @if (Route::has('useraccess.menu.create') && Route::has('useraccess.menu.store'))
        <a href="{{ route('useraccess.menu.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm transition font-medium">Create Menu</a>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md" role="alert">{{ session('success') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 display" id="menuTable">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action URL</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Route Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prefix</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Controller Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Route Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Menu Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-350">
                    <?php foreach ($menus as $menu) { ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo $menu['id']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100"><?php echo $menu['name']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo $menu['action']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo json_decode($menu['extra_options'], true)['route_name'] ?? ''; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo json_decode($menu['extra_options'], true)['prefix'] ?? ''; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo $menu['controller']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo $menu['method']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap uppercase text-xs font-bold"><?php echo $menu['route_type']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap"><?php echo implode(',', $menu['access_types']); ?></td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <?php echo ($menu['status'] == 1 || $menu['status'] == "1") 
                                    ? '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400"> Active </span>' 
                                    : '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400"> Deactive </span>'; ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap flex items-center space-x-2">
                                @if (Route::has('useraccess.menu.edit') && Route::has('useraccess.menu.update'))
                                <a href="<?php echo route("useraccess.menu.edit", $menu['id']) ?>" class="p-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded transition text-xs flex items-center justify-center w-8 h-8"><i class="fa fa-pencil"></i></a>
                                @endif
                                @if (Route::has('useraccess.menu.delete'))
                                <form action="<?php echo route('useraccess.menu.delete', $menu['id']); ?>" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-red-600 hover:bg-red-700 text-white rounded transition text-xs flex items-center justify-center w-8 h-8"><i class="fa fa-trash"></i></button>
                                </form>
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
