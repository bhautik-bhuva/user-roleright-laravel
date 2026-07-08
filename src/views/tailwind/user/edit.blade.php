@extends('laravelMain::' . $layout_file)
@section('title', 'Edit User')
@push('styles')
    <link href="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.css')}}" rel="stylesheet" type="text/css" id="skinSheet">
    <style>
        .hierarchy-checkboxes label {
            display: inline-block;
            margin-bottom: 0;
            font-size: 0.875rem;
            color: #374151;
        }
        .hierarchy-node {
            margin-top: 0.25rem;
            margin-bottom: 0.25rem;
        }
    </style>
@endpush
@section('content')
<div class="container mx-auto px-4 py-6">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md" role="alert">{{ session('success') }}</div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Edit User</h4>
        @if (Route::has('useraccess.user.list'))
            <a class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition font-medium text-gray-700 dark:text-gray-200" href="{{ route('useraccess.user.list') }}">Back</a>
        @endif
    </div>

    @if (Route::has('useraccess.user.update'))
        <form action="{{ route('useraccess.user.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input id="name" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror opacity-60" name="name" value="{{ old('name', $user->name) }}" readonly autocomplete="name" autofocus disabled>
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                        <input id="email" type="email" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror opacity-60" name="email" value="{{ old('email', $user->email) }}" readonly autocomplete="email" disabled>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Role</label>
                        <select class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('role') border-red-500 @enderror" id="role" name="role">
                            <option value="" <?php echo (old('role') == "") ? 'selected' : ''; ?>>Select Role</option>
                            @foreach ($allroles as $role)
                            <option value="{{ $role['id'] }}" <?php echo (old('role', $role_id) == $role['id']) ? 'selected' : ''; ?>>{{ $role['name'] }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition shadow-sm">Update</button>
                </div>
            </div>

            <div class="flex justify-between items-center my-4">
                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100">Assign Permissions</h4>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 overflow-y-auto relative" id="roleAccesstree" style="height:600px;">
                <div class="row-fluid" id="accrss_tree">
                    <div class="hierarchy-checkboxes" rel="test">
                        <input class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 hierarchy-root-checkbox" type="checkbox" name="selNodes_all[]" id="all" value="All">
                        <label class="hierarchy-root-label text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">All Permissions</label>
                        <div class="hierarchy-root-child hierarchy-node space-y-2 mt-2 pl-4" style="width:95%">
                            @php $i= 0; @endphp
                            @foreach ($allRoutes as $k => $v)
                                <div class="hierarchy-node middle_node_{{ $i }} border-l-2 border-gray-100 dark:border-gray-750 pl-4 py-1">
                                    <input class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 hierarchy-checkbox" type="checkbox">
                                    <label class="hierarchy-label text-sm font-semibold text-gray-800 dark:text-gray-200 ml-1">{{ $k }}</label>
                                        @foreach ($v as $kk => $vv)
                                            <div class="hierarchy-node leaf flex items-center pl-4">
                                                <input class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 hierarchy-checkbox middle_node_{{ $i }}" id="node_{{ $vv['id'] }}" type="checkbox" name="selNodes[]" value="{{ $vv['id'] }}" <?php echo (in_array($vv['id'], $roleActions) || in_array($vv['id'], $userRightActions)) ? 'checked' : ''; ?> <?php echo (in_array($vv['id'], $roleActions)) ? 'disabled' : ''; ?>>
                                                <label class="hierarchy-label text-xs ml-2 text-gray-600 dark:text-gray-400">
                                                    {{ $vv['menu_label'] }}
                                                    {{ ($vv['menu_status'] == 1)?'(Admin Menu)':'' }}
                                                    @php $extra_options_imvalue = json_decode($vv["extra_options"],1);
                                                    $prefix_imvalue =  ($extra_options_imvalue["prefix"] ?? "") ? '/'.$extra_options_imvalue["prefix"] : ""; @endphp
                                                    <span class='text-indigo-600 dark:text-indigo-400'> | URL => {{ $prefix_imvalue.$vv['action'] }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                </div>
                                @php $i++; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" role="alert">You do not have permission to edit a user.</div>
    @endif
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.js')}}"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#roleAccesstree').append($('.hierarchy-root-child'));
        }, 500);
        const $thisNode = $('.expand-collapse-button').parent();

        $('.expand-collapse-button').on('click', function() {
            if ($thisNode.hasClass("child-expanded")) {
                $('.hierarchy-root-child').css({
                    left: '20px',
                    top: '50px'
                });
            } else {
                $('.hierarchy-root-child').css({
                    left: '0px',
                    top: '0px'
                });
            }
        });

        if (!$thisNode.hasClass("child-expanded")) {
            $('.expand-collapse-button').trigger('click');
        }
    });

    setTimeout(() => {
        const checkedInputs = $('.hierarchy-node.leaf.child-expanded').parent('div');
        var groups = {};

        $(checkedInputs).find('.hierarchy-checkbox').each(function() {
            var classes = $(this).attr('class').split(' ');
            var groupClass = classes.find(cls => cls.startsWith('middle_node_'));
            if (!groups[groupClass]) {
                groups[groupClass] = {
                    total: 0,
                    checked: 0
                };
            }
            groups[groupClass].total++;

            if ($(this).is(':checked')) {
                groups[groupClass].checked++;
            }
        });
        $.each(groups, function(i, val) {
            if (val['total'] === val['checked']) {
                $('.' + i + ' .hierarchy-checkbox').eq(0).prop('checked', true);
                $('.' + i + ' .hierarchy-checkbox').eq(0).prop('disabled', true);
            }
        });

    }, 200);
</script>
@endpush
