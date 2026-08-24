@extends('laravelMain::' . $layout_file)
@section('title', 'Edit User')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    {!! file_get_contents(base_path() . '/vendor/techaxion/user-roleright-laravel/src/assets/hierarchical/hierarchical-bootstrap.css') !!}
</style>
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->
@endpush
@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit User</h4>
        @if (Route::has('useraccess.user.list'))
        <a class="btn btn-light btn-sm border" href="{{ route('useraccess.user.list') }}">Back</a>
        @endif
    </div>
    @if (Route::has('useraccess.user.update'))

    <form action="{{ route('useraccess.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row mt-3">
                    <div class="form-group col-lg-6">
                        <label for="name" class="text-black">Name</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" readonly autocomplete="name" autofocus disabled>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="method" class="text-black">Email Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" readonly autocomplete="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <!-- <div class="row mt-3">
                        <div class="form-group col-lg-6">
                            <label for="action" class="text-black">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="menu_status" class="text-black">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" >
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div> -->
                <div class="row mt-3">
                    <div class="form-group col-lg-6">
                        <label for="module_label" class="text-black">Select Role</label>
                        <select class="form-control form-select @error('role') is-invalid @enderror" id="role" name="role">
                            <option value="" <?php echo (old('role') == "") ? 'selected' : ''; ?>>Select Role</option>
                            @foreach ($allroles as $role)
                            <option value="{{ $role['id'] }}" <?php echo (old('role', $role_id) == $role['id']) ? 'selected' : ''; ?>>{{ $role['name'] }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Update</button>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center my-3">
            <h4 class="mb-0">Assign Permissions</h5>
        </div>
        <div class="card mb-30" >
            <div class="card-body" id="roleAccesstree" style="height:600px; overflow-y: scroll;">
                <div class="row-fluid" id="accrss_tree">
                    <div class="hierarchy-checkboxes" rel="test">
                        <input class="hierarchy-root-checkbox" type="checkbox" name="selNodes_all[]" id="all" value="All">
                        <label class="hierarchy-root-label">All Permission's</label>
                        <div class="hierarchy-root-child hierarchy-node" style="width:95%">
                            @php $i= 0; @endphp
                            @foreach ($allRoutes as $k => $v)
                            <div class="hierarchy-node middle_node_{{ $i }}">
                                <input class="hierarchy-checkbox" type="checkbox">
                                <label class="hierarchy-label"> &nbsp; {{ $k }}</label>
                                @foreach ($v as $kk => $vv)
                                <div class="hierarchy-node leaf">
                                    <input class="hierarchy-checkbox middle_node_{{ $i }}" id="node_{{ $vv['id'] }}" type="checkbox" name="selNodes[]" value="{{ $vv['id'] }}" <?php echo (in_array($vv['id'], $roleActions) || in_array($vv['id'], $userRightActions)) ? 'checked' : ''; ?> <?php echo (in_array($vv['id'], $roleActions)) ? 'disabled' : ''; ?>>
                                    <label class="hierarchy-label"> &nbsp; {{ $vv['menu_label'] }}
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
    <div class="alert alert-danger" role="alert">You do not have permission to edit an user.</div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    {!! file_get_contents(dirname(__DIR__,3).'/vendor/techaxion/user-roleright-laravel/src/assets/hierarchical/hierarchical-checkboxes.js') !!}
</script>
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
