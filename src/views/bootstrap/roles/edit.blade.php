@extends('laravelMain::' . $layout_file)
@section('title', 'Edit Role')
@push('styles')
    <style>
        {!! file_get_contents(base_path() . '/vendor/techaxion/user-roleright-laravel/src/assets/hierarchical/hierarchical-bootstrap.css') !!}
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h4 class="mb-0">Edit Role</h4>
        @if (Route::has('useraccess.role.list'))
            <a href="{{ route('useraccess.role.list') }}" class="btn btn-sm btn-light border">Back</a>
        @endif
    </div>
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if (Route::has('useraccess.role.update'))
        <form method="POST" action="{{ route('useraccess.role.update', $role->id) }}" enctype="multipart/form-data" id="roleForm" >
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="form-row row">
                        <div class="form-group col-md-4">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter role name" value="{{ old('name', $role->name) }}">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="access" class="form-label">Access Type</label>
                            <select class="form-control form-select @error('access') is-invalid @enderror" id="access" name="access">
                                <option value="" <?php echo (old('access', $role->access) == "") ? 'selected' : ''; ?>>Select Access Type</option>
                                <option value="All" <?php echo (old('access', $role->access) == "All") ? 'selected' : ''; ?>>All</option>
                                <option value="Excluded" <?php echo (old('access', $role->access) == "Excluded") ? 'selected' : ''; ?>>Excluded</option>
                                <option value="Selected" <?php echo (old('access', $role->access) == "Selected") ? 'selected' : ''; ?>>Selected</option>
                                <option value="None" <?php echo (old('access', $role->access) == "None") ? 'selected' : ''; ?>>None</option>
                            </select>
                            @error('access')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="interface_access" class="form-label">Interface Access</label>
                            @php 
                                $selectedAccessFor = old('interface_access', explode(',', $role->interface_access ?? ''));
                                if (!is_array($selectedAccessFor)) {
                                    $selectedAccessFor = explode(',', $selectedAccessFor);
                                }
                            @endphp
                            <select class="form-control form-select @error('interface_access') is-invalid @enderror" id="interface_access" multiple name="interface_access[]">
                                @foreach ($accessFor as $k => $v)
                                    <option value="{{ $v['id'] }}"<?php echo (in_array($v['id'], $selectedAccessFor)) ? 'selected' : ''; ?> >{{ $v['name'] }}</option>
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
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Enter description or short note" maxlength="255">{{ old('description', $role->description) }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center my-3">
                <h4 class="mb-0">Assign Permissions</h5>
            </div>
            <div class="card mb-30" style="height:600px; overflow-y: scroll;">
                <div class="card-body" id="roleAccesstree"></div>
            </div>

        </form>
    @else
        <div class="alert alert-danger" role="alert">You do not have permission to edit a role.</div>
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
            placeholder: "Select Access For"
        });
        setTimeout(function(){
            $('#roleAccesstree').append( $('.hierarchy-root-child') );
        }, 500);
        
        $(document).on('click', '.expand-collapse-button', function () {
            const $thisNode = $(this).parent();
            if ($thisNode.hasClass('hierarchy-checkboxes')) {
                if ($thisNode.hasClass("child-expanded")) {
                    $('.hierarchy-root-child').css({left: '20px',top: '50px'});
                } else { 
                    $('.hierarchy-root-child').css({left: '0px', top: '0px' });
                }
            }
        });
        
        const $thisNode = $('.expand-collapse-button').parent();
        if (!$thisNode.hasClass("child-expanded")) {
            $('.expand-collapse-button').trigger('click');
        }
        var initialAccessFor = $('#interface_access').val();
        if (initialAccessFor) {
            fetchPermissions(initialAccessFor);
        }
    });
    setTimeout(() => {
        checkedOptions();
    }, 200);
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
        var roleActions = "<?php echo json_encode($roleActions); ?>";        
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
                            <label class="hierarchy-root-label">All Permissions</label>  
                        </div>
                    </div>`;
                  action_html += '<div class="hierarchy-root-child hierarchy-node" style="width: 95%; left: 20px; top: 50px; display: block;" rel="test">';
                if (response.data) {
                    $.each(response.data, function(module, actions) {
                        action_html += `<div class="hierarchy-node child-expanded">`;
                        action_html += `<input class="hierarchy-checkbox" id="middle_node_${module}" type="checkbox">`;
                        action_html += `<label class="hierarchy-label">${module}</label>`;
                        $.each(actions, function(index, action) {
                            // console.log('Initial roleActions:', roleActions , action.id.toString());
                            ids.push(action.id.toString());
                            action_html += `<div class="hierarchy-node leaf " data-action-id="${action.id}">`;
                            action_html += `<input class="hierarchy-checkbox" id="node_${action.id}" type="checkbox" name="selNodes[]" value="${action.id}" 
                            ${roleActions.includes(Number(action.id.toString())) ? 'checked' : ''}>`;
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
                setTimeout(() => {
                    initHierarchicalCheckboxes("#roleAccesstree");
                    checkedOptions();
                }, 200); 
            },
            error: function() {
            }
        });
    }
    function initHierarchicalCheckboxes(selector) {
        const $el = selector ? $(selector) : $(document);
        // $el.find(".hierarchy-root-child div div").hide().parent().removeClass("child-expanded");
        $el.find(".hierarchy-checkboxes, .hierarchy-root-child, .hierarchy-node").each(function () {
            const $this = $(this);
            if ($this.find("> .expand-collapse-button").length === 0) {
                $this.prepend('<div class="expand-collapse-button"></div>');
            }
        });

        // Update parent checkbox states based on children checked state
        $el.find(".hierarchy-root-child > .hierarchy-node").each(function() {
            const $middleNode = $(this);
            const $middleCheckbox = $middleNode.children("input.hierarchy-checkbox");
            const $leafCheckboxes = $middleNode.find(".leaf input.hierarchy-checkbox");
            if ($leafCheckboxes.length > 0) {
                const allChecked = $leafCheckboxes.length === $leafCheckboxes.filter(":checked").length;
                $middleCheckbox.prop("checked", allChecked);
            }
        });

        // Update root checkbox states based on children checked state
        $el.find(".hierarchy-checkboxes").each(function() {
            const $root = $(this);
            const rel = $root.attr("rel");
            const $rootChild = $(".hierarchy-root-child[rel=" + rel + "]");
            const $rootCheckbox = $root.find(".hierarchy-root-checkbox");
            const $allCheckboxes = $rootChild.find("input.hierarchy-checkbox");
            if ($allCheckboxes.length > 0) {
                const allChecked = $allCheckboxes.length === $allCheckboxes.filter(":checked").length;
                $rootCheckbox.prop("checked", allChecked);
            }
        });
    }
    function checkedOptions() {
        const checkedInputs = $('.hierarchy-node.leaf.child-expanded').parent('div');
        var groups = {};

        $(checkedInputs).find('.hierarchy-checkbox').each(function () {
            var classes = $(this).attr('class').split(' ');
            var groupClass = classes.find(cls => cls.startsWith('middle_node_'));
            if (!groups[groupClass]) {
                groups[groupClass] = { total: 0, checked: 0 };
            }
            groups[groupClass].total++;

            if ($(this).is(':checked')) {
                groups[groupClass].checked++;
            }
        });
        $.each(groups,function (i, val) {
            if(val['total'] === val['checked']){
                $('.' +  i  + ' .hierarchy-checkbox').eq(0).prop('checked', true);
            }
        });
    }
</script>
@endpush
