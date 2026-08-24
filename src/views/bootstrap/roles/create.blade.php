         @extends('laravelMain::' . $layout_file)
@section('title', 'Create Role')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        {!! file_get_contents(base_path() . '/vendor/techaxion/user-roleright-laravel/src/assets/hierarchical/hierarchical-bootstrap.css') !!}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--multiple {
            border-color: rgb(209, 213, 219);
            border-radius: 0.375rem;
            padding: 2px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Create Role</h4>
        @if (Route::has('useraccess.role.list'))
            <a href="{{ route('useraccess.role.list') }}" class="btn btn-sm btn-light border">Back</a>
        @endif
    </div>
    @if (Route::has('useraccess.role.store'))
        <form method="POST" action="{{ route('useraccess.role.store') }}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-row row">
                        <div class="form-group col-md-4">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter role name" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="access" class="form-label">Access Type</label>
                            <select class="form-control form-select @error('access') is-invalid @enderror" id="access" name="access">
                                <option value="" <?php echo (old('access') == "") ? 'selected' : ''; ?>>Select Access Type</option>
                                <option value="All" <?php echo (old('access') == "All") ? 'selected' : ''; ?>>All</option>
                                <option value="Excluded" <?php echo (old('access') == "Excluded") ? 'selected' : ''; ?>>Excluded</option>
                                <option value="Selected" <?php echo (old('access') == "Selected") ? 'selected' : ''; ?>>Selected</option>
                                <option value="None" <?php echo (old('access') == "None") ? 'selected' : ''; ?>>None</option>
                            </select>
                            @error('access')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="interface_access" class="form-label">Interface Access</label>
                            <select class="form-control form-select @error('interface_access') is-invalid @enderror" id="interface_access" multiple name="interface_access[]">
                                @foreach ($accessFor as $k => $v)
                                    <option value="{{ $v['id'] }}" <?php echo (old('interface_access') == $v['id']) ? 'selected' : ''; ?> >{{ $v['name'] }}</option>
                                @endforeach
                            </select>
                            @error('interface_access')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Enter description or short note" maxlength="255">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Save & continue</button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center my-3">
                <h4 class="mb-0">Assign Permissions</h5>
            </div>
            <div class="card mb-30" style="height:600px; overflow-y: scroll;">
                <div class="card-body"  id="roleAccesstree" >
                        <div class="row-fluid" id="accrss_tree" >
                            <div class="hierarchy-checkboxes" rel="test">
                                <input class="hierarchy-root-checkbox" type="checkbox" name="selNodes_all[]" id="all" value="All">
                                <label  class="hierarchy-root-label">All Permission's</label>
                                <div class="hierarchy-root-child hierarchy-node" style="width:95%">
                                @foreach ($allRoutes as $k => $v)
                                    <div class="hierarchy-node">
                                        <input class="hierarchy-checkbox" id="middle_node_{{ $k }}" type="checkbox">
                                        <label class="hierarchy-label" > &nbsp; {{ $k }}</label>
                                        @foreach ($v as $kk => $vv)
                                            <div class="hierarchy-node leaf" data-action-id="{{ $vv['id'] }}">
                                                <input class="hierarchy-checkbox" id="node_{{ $vv['id'] }}" type="checkbox" name="selNodes[]" value="{{ $vv['id'] }}">
                                                <label class="hierarchy-label"  > &nbsp; {{ $vv['menu_label'] }}
                                                    {{ ($vv['menu_status'] == 1)?'(Admin Menu)':'' }}
                                                    @php $extra_options_imvalue = json_decode($vv["extra_options"],1);
                                                    $prefix_imvalue =  $extra_options_imvalue["prefix"] ? '/'.$extra_options_imvalue["prefix"] : ""; @endphp
                                                    <span class='text-primary'> | URL => {{ $prefix_imvalue.$vv['action'] }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    @else
        <div class="alert alert-danger" role="alert">You do not have permission to create a role.</div>
    @endif
</div>
@endsection
@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    {!! file_get_contents(dirname(__DIR__,3).'/vendor/techaxion/user-roleright-laravel/src/assets/hierarchical/hierarchical-checkboxes.js') !!}
</script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function(){
        $("#interface_access").change(function(){
            fetchPermissions($(this).val());
        });
        $('#interface_access').select2({
            placeholder: "Select Interface Access"
        });
        setTimeout(function(){
            $('#roleAccesstree').append( $('.hierarchy-root-child') );
        }, 500);
        
        $(document).on('click', '.expand-collapse-button', function () {
            const $thisNode = $(this).parent();
            if ($thisNode.hasClass("child-expanded")) {
                $('.hierarchy-root-child').css({left: '20px', top: '50px'});
            } else {
                $('.hierarchy-root-child').css({left: '0px', top: '0px'});
            }
        });
        
        const $thisNode = $('.expand-collapse-button').parent();
        if (!$thisNode.hasClass("child-expanded")) {
            $('.expand-collapse-button').trigger('click');
        }

        var initialAccessFor = $('#interface_access').val();
        if (initialAccessFor) {
            // fetchPermissions(initialAccessFor);
        }
    }); 
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

    function fetchPermissions(accessForId) {
       
        $.ajax({
            url: "{{ url('/useraccess/role/permissions') }}",
            method: 'GET',
            data: { interface_access: accessForId },
            dataType: 'json',
            success: function(response) {
                var ids = [];
                var action_html = 
                    `<div class="row-fluid" id="accrss_tree">
                        <div class="hierarchy-checkboxes child-expanded" rel="test"> 
                            <input class="hierarchy-root-checkbox" type="checkbox" name="selNodes_all[]" id="all" value="All">
                            <label class="hierarchy-root-label">All Permission's</label>                                
                        </div>
                    </div>`;
                  action_html += '<div class="hierarchy-root-child hierarchy-node" style="width: 95%;display: block;left: 20px; top: 50px;" rel="test">';
                if (response.data) {
                    $.each(response.data, function(module, actions) {
                        action_html += `<div class="hierarchy-node child-expanded">`;
                        action_html += `<input class="hierarchy-checkbox" id="middle_node_${module}" type="checkbox">`;
                        action_html += `<label class="hierarchy-label">${module}</label>`;
                        $.each(actions, function(index, action) {
                            ids.push(action.id.toString());
                            action_html += `<div class="hierarchy-node leaf" data-action-id="${action.id}">`;
                            action_html += `<input class="hierarchy-checkbox" id="node_${action.id}" type="checkbox" name="selNodes[]" value="${action.id}">`;
                            action_html += `<label class="hierarchy-label">`;
                            action_html += `${action.menu_label} ${action.menu_status == 1?'(Admin Menu)':'' }`;
                            
                                let extra_options_imvalue = {};
                                try {
                                    extra_options_imvalue = JSON.parse(action.extra_options || '{}');
                                } catch (e) {
                                    extra_options_imvalue = {};
                                }
                                let prefix_imvalue = extra_options_imvalue.prefix ? `/${extra_options_imvalue.prefix}` : '';

                                action_html += `<span class='text-primary'> | URL => ${prefix_imvalue}${action.action}</span>`;
                            action_html += `</label>`;
                            action_html += `</div>`;
                        });
                        action_html += `</div>`;
                    });
                }
                action_html += `</div>`;
                $("#roleAccesstree").html(action_html);  
                
                // Initialize the tree structure on the newly created elements
                initHierarchicalCheckboxes("#roleAccesstree");

                // Expand all nodes by triggering click on all expand-collapse buttons (just like page load)
                $('#roleAccesstree .expand-collapse-button').each(function() {
                    const $parent = $(this).parent();
                    if (!$parent.hasClass("child-expanded")) {
                        $(this).trigger('click');
                    }
                });
            },
            error: function() {
            }
        });
    } 
</script>
@endpush
