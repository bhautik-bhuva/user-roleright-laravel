@extends('laravelMain::' . $layout_file)
@section('title', 'Create Role')
@push('styles')
    <link href="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.css')}}" rel="stylesheet" type="text/css" id="skinSheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Create Role</h4>
        @if (Route::has('useraccess.role.list'))
            <a href="{{ route('useraccess.role.list') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition font-medium text-gray-700 dark:text-gray-200">Back</a>
        @endif
    </div>

    @if (Route::has('useraccess.role.store'))
        <form method="POST" action="{{ route('useraccess.role.store') }}" class="space-y-6">
            @csrf
            
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role Name</label>
                        <input type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror" id="name" name="name" placeholder="Enter role name" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="access" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Access Type</label>
                        <select class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('access') border-red-500 @enderror" id="access" name="access">
                            <option value="" <?php echo (old('access') == "") ? 'selected' : ''; ?>>Select Access Type</option>
                            <option value="All" <?php echo (old('access') == "All") ? 'selected' : ''; ?>>All</option>
                            <option value="Excluded" <?php echo (old('access') == "Excluded") ? 'selected' : ''; ?>>Excluded</option>
                            <option value="Selected" <?php echo (old('access') == "Selected") ? 'selected' : ''; ?>>Selected</option>
                            <option value="None" <?php echo (old('access') == "None") ? 'selected' : ''; ?>>None</option>
                        </select>
                        @error('access')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="access_for" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Access For</label>
                        <select class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('access_for') border-red-500 @enderror" id="access_for" name="access_for">
                            <option value="">Select Access For</option>
                            @foreach ($accessFor as $k => $v)
                                <option value="{{ $v['id'] }}" <?php echo (old('access_for') == $v['id']) ? 'selected' : ''; ?> >{{ $v['name'] }}</option>
                            @endforeach
                        </select>
                        @error('access_for')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror" id="description" name="description" rows="3" placeholder="Enter description or short note" maxlength="255">{{ old('description') }}</textarea>
                </div>

                <div class="mt-6">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition shadow-sm">Save & continue</button>
                </div>
            </div>

            <div class="flex justify-between items-center my-4">
                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100">Assign Permissions</h4>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 overflow-y-auto relative" id="roleAccesstree" style="height:600px;">
                <div class="row-fluid" id="accrss_tree" >
                    <div class="hierarchy-checkboxes" rel="test">
                        <input class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 hierarchy-root-checkbox" type="checkbox" name="selNodes_all[]" id="all" value="All">
                        <label class="hierarchy-root-label text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">All Permissions</label>
                        <div class="hierarchy-root-child hierarchy-node space-y-2 mt-2 pl-4" style="width:95%">
                            @foreach ($allRoutes as $k => $v)
                                <div class="hierarchy-node border-l-2 border-gray-100 dark:border-gray-750 pl-4 py-1">
                                    <input class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 hierarchy-checkbox" id="middle_node_{{ $k }}" type="checkbox">
                                    <label class="hierarchy-label text-sm font-semibold text-gray-800 dark:text-gray-200 ml-1">{{ $k }}</label>
                                        @foreach ($v as $kk => $vv)
                                            <div class="hierarchy-node leaf flex items-center pl-4" data-action-id="{{ $vv['id'] }}">
                                                <input class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 hierarchy-checkbox" id="node_{{ $vv['id'] }}" type="checkbox" name="selNodes[]" value="{{ $vv['id'] }}">
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </form>
    @else
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" role="alert">You do not have permission to create a role.</div>
    @endif
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.js')}}"></script>
<script>
    $(document).ready(function(){
        setTimeout(function(){
            $('#roleAccesstree').append( $('.hierarchy-root-child') );
        }, 500);
        const $thisNode = $('.expand-collapse-button').parent();

        $('.expand-collapse-button').on('click', function() {
            if ($thisNode.hasClass("child-expanded")) {
                $('.hierarchy-root-child').css({left: '20px', top: '50px'});
            } else {
                $('.hierarchy-root-child').css({left: '0px', top: '0px'});
            }
        });

        if (!$thisNode.hasClass("child-expanded")) {
            $('.expand-collapse-button').trigger('click');
        }

        var initialAccessFor = $('#access_for').val();
        if (initialAccessFor) {
            fetchPermissions(initialAccessFor);
        }
    });

    function updatePermissionTree(actionIds) {
        var ids = (actionIds || []).map(String);

        // $('.hierarchy-node.leaf').each(function() {
        //     var actionId = $(this).data('action-id');
        //     if (!actionId) {
        //         return;
        //     }
        //     var show = ids.length === 0 || ids.includes(actionId.toString());
        //     if (show) {
        //         $(this).show();
        //     } else {
        //         $(this).hide().find('input[type=checkbox]').prop('checked', false);
        //     }
        // });

        // $('.hierarchy-node').not('.leaf').each(function() {
        //     var hasVisible = $(this).find('.hierarchy-node.leaf:visible').length > 0;
        //     $(this).toggle(hasVisible);
        // });
    }

    function fetchPermissions(accessForId) {
        if (!accessForId) {
            updatePermissionTree([]);
            return;
        }

        $.ajax({
            url: "{{ url('/useraccess/role/permissions') }}",
            method: 'GET',
            data: { access_for: accessForId },
            dataType: 'json',
            success: function(response) {
                var ids = [];
                var action_html = '';
                if (response.data) {
                    $.each(response.data, function(module, actions) {
                        action_html += `<div class="hierarchy-node border-l-2 border-gray-100 dark:border-gray-750 pl-4 py-1">`;
                        action_html += `<input class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 hierarchy-checkbox" id="middle_node_${module}" type="checkbox">`;
                        action_html += `<label class="hierarchy-label text-sm font-semibold text-gray-800 dark:text-gray-200 ml-1">${module}</label>`;
                        $.each(actions, function(index, action) {
                            ids.push(action.id.toString());
                          
                           
                        });
                        action_html += `</div>`;
                    });
                }
                // $(".hierarchy-root-child").html(action_html);  
                console.log('Fetched action IDs:', ids);
                console.log('action_html :', action_html);
                updatePermissionTree(ids);
            },
            error: function() {
                updatePermissionTree([]);
            }
        });
    }

    $("#access").change(function(){
        var selected = $(this).val();
        if(selected == 'All' ){
            $(".hierarchy-checkbox,.hierarchy-root-checkbox").prop("checked", true);
        }else if(selected == 'Selected' || selected == 'Excluded'  || selected == 'None'){
            if($("#all").prop("checked") == true){
                $(".hierarchy-checkbox,.hierarchy-root-checkbox").prop("checked", true);
            }
            $(".hierarchy-checkbox,.hierarchy-root-checkbox").prop("checked", false);
        }
    });

    $("#access_for").change(function(){
        fetchPermissions($(this).val());
    });
</script>
@endpush
