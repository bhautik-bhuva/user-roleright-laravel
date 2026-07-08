@extends('laravelMain::' . $layout_file)
@section('title', 'Create Role')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.css')}}" rel="stylesheet" type="text/css" id="skinSheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                            <label for="access_for" class="form-label">Access For</label>
                            <select class="form-control form-select @error('access_for') is-invalid @enderror" id="access_for" name="access_for">
                                <option value="">Select Access For</option>
                                @foreach ($accessFor as $k => $v)
                                    <option value="{{ $v['id'] }}" <?php echo (old('access_for') == $v['id']) ? 'selected' : ''; ?> >{{ $v['name'] }}</option>
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
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Enter description or short note" maxlength="255">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Save & continue</button>
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
