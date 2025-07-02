// create new roles
@extends('laravelMain::contentNavbarLayout')
@section('title', 'Create Role')

@section('content')
<h4> Create New Role </h4> <hr>
<form method="POST" action="{{ route('.role.add') }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="form-row row">
                <div class="form-group col-md-4">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter role name">
                </div>
                <div class="form-group col-md-4">
                    <label for="access" class="form-label">Access Type</label>
                    <select class="form-control form-select" id="access" name="access" required>
                        <option value="all">All</option>
                        <option value="selected">Selected</option>
                        <option value="exclude">Exclude</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="access_for" class="form-label">Access For</label>
                    <input type="text" class="form-control" id="access_for" name="access_for" required placeholder="Admin, Team Lead, User, etc.">
                </div>
                <div class="form-group col-md-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description or short note" maxlength="255"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save & continue</button>
        </div>
    </div>

    <div class="card mt-3 mb-30" id="roleAccesstree"  style="height:600px; overflow-y: scroll;">
        <div class="card-body">
            <h5>Assign Permissions</h5>
            <div class="row-fluid" id="access_tree" >
                <div class="hierarchy-checkboxes" rel="access_tree">
                    <input class="hierarchy-root-checkbox" type="checkbox" name="selNodes[]" id="all" value="All">
                    <label class="hierarchy-root-label">All Permission's</label>
                    <div class="hierarchy-root-child hierarchy-node" style="width:95%;">
                        @foreach ($allRoutes as $k => $v)
                            <div class="hierarchy-node">
                                <input class="hierarchy-checkbox"  type="checkbox">
                                <label class="hierarchy-label"> &nbsp; {{ $k }}</label>
                                @foreach ($v as $kk => $vv)
                                    <div class="hierarchy-node leaf">
                                        <input class="hierarchy-checkbox" id="node_{{ $vv['id'] }}" type="checkbox" name="selNodes[]" value="{{ $vv['id'] }}">
                                        <label class="hierarchy-label"> &nbsp; {{ $vv['menu_label'] }}
                                            {{ ($vv['menu_status'] == 1)?'(Admin Menu)':'' }}
                                            <span class='text-primary'> | URL => {{ $vv['action'] }}</span>
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

<link href="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.css')}}" rel="stylesheet" type="text/css" id="skinSheet">
<script src="{{asset('assets/vendor/useraccess/hierarchical/hierarchical-checkboxes.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){ 
            $('#roleAccesstree').append( $('.hierarchy-root-child') );
        }, 500);
        const $thisNode = $('.expand-collapse-button').parent();
        
        $('.expand-collapse-button').on('click', function() {
            if ($thisNode.hasClass("child-expanded")) {
                $('.hierarchy-root-child').css({left: '27px', top: '90px'});
            } else {
                $('.hierarchy-root-child').css({left: '0px', top: '0px'});
            }
        });
        
        if (!$thisNode.hasClass("child-expanded")) {
            $('.expand-collapse-button').trigger('click');
        }
    });
    $("#access_type").change(function(){
        var selected = $(this).val();
        if(selected == 'All'){
            $("#all").trigger('click');
        }
        if(selected == 'Selected' || selected == 'None'){
            if($("#all").prop("checked") == true){
                $("#all").trigger('click');
            }
        }
    });
</script>
@endsection