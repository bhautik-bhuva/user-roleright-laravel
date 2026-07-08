@extends('laravelMain::' . $layout_file)
@section('title', 'Edit Role')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.css')}}" rel="stylesheet" type="text/css" id="skinSheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                            <label for="access_for" class="form-label">Access For</label>
                            <select class="form-control form-select @error('access_for') is-invalid @enderror" id="access_for" name="access_for">
                                <option value="">Select Access For</option>
                                @foreach ($accessFor as $k => $v)
                                    <option value="{{ $v['id'] }}" <?php echo (old('access_for', $role->access_for) == $v['id'] ) ? 'selected' : ''; ?> >{{ $v['name'] }}</option>
                                @endforeach
                            </select>
                            @error('access_for')
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
            <div class="card mb-30" id="roleAccesstree"  style="height:600px; overflow-y: scroll;">
                <div class="card-body">
                        <div class="row-fluid" id="accrss_tree" >
                            <div class="hierarchy-checkboxes" rel="test">
                                <input class="hierarchy-root-checkbox" type="checkbox" name="selNodes_all[]" id="all" value="All">
                                <label  class="hierarchy-root-label">All Permission's</label>
                                <div class="hierarchy-root-child hierarchy-node" style="width:95%">
                                @php $i= 0; @endphp
                                @foreach ($allRoutes as $k => $v)
                                    <div class="hierarchy-node middle_node_{{ $i }}">
                                        <input class="hierarchy-checkbox" type="checkbox">
                                        <label class="hierarchy-label" > &nbsp; {{ $k }}</label>
                                        @foreach ($v as $kk => $vv)
                                            <div class="hierarchy-node leaf">
                                                <input class="hierarchy-checkbox middle_node_{{ $i }}" id="node_{{ $vv['id'] }}" type="checkbox" name="selNodes[]" value="{{ $vv['id'] }}" <?php echo (in_array($vv['id'], $roleActions))?'checked':''; ?>>
                                                <label class="hierarchy-label" > &nbsp; {{ $vv['menu_label'] }}
                                                    {{ ($vv['menu_status'] == 1)?'(Admin Menu)':'' }}
                                                    @php $extra_options_imvalue = json_decode($vv["extra_options"],1);
                                                    $prefix_imvalue =  $extra_options_imvalue["prefix"] ? '/'.$extra_options_imvalue["prefix"] : ""; @endphp
                                                    <span class='text-primary'> | URL => {{ $prefix_imvalue.$vv['action'] }}</span>
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
            </div>

        </form>
    @else
        <div class="alert alert-danger" role="alert">You do not have permission to edit a role.</div>
    @endif
</div>
@endsection
@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.js')}}"></script>

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
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

    setTimeout(() => {
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

    }, 200);

    function updatePermissionTree(actionIds) {
        var ids = (actionIds || []).map(String);

        $('.hierarchy-node.leaf').each(function() {
            var actionId = $(this).data('action-id');
            if (!actionId) {
                return;
            }
            var show = ids.length === 0 || ids.includes(actionId.toString());
            if (show) {
                $(this).show();
            } else {
                $(this).hide().find('input[type=checkbox]').prop('checked', false);
            }
        });

        $('.hierarchy-node').not('.leaf').each(function() {
            var hasVisible = $(this).find('.hierarchy-node.leaf:visible').length > 0;
            $(this).toggle(hasVisible);
        });
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
                if (response.data) {
                    $.each(response.data, function(module, actions) {
                        $.each(actions, function(index, action) {
                            ids.push(action.id.toString());
                        });
                    });
                }
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
