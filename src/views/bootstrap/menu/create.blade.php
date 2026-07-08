@extends('laravelMain::' . $layout_file)
@section('title', 'Create Menu')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->
@endpush
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Create Menu</h4>
        @if (Route::has('useraccess.menu.list'))
            <a class="btn btn-sm btn-light border" href="{{ route('useraccess.menu.list') }}">Back</a>
        @endif
    </div>
    @if (Route::has('useraccess.menu.store'))
        <div class="card">
            <div class="card-body">
                <form action="{{ route('useraccess.menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(session('route_error'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ session('route_error') }}</strong>
                        </span>
                    @endif
                    <div class="row mt-3">
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="name" class="text-black">Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ old('name') }}" >
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="controller" class="text-black">Controller Name</label>
                            <select name="controller" id="controller" class="form-control form-select @error('controller') is-invalid @enderror">
                                <option value="">Select Controller</option>
                                <?php foreach ($data['controllers'] as $key => $value): ?>
                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                <?php endforeach ?>
                            </select>
                            @error('controller')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="method" class="text-black">Method Name</label>
                            <select name="method" id="method" class="form-control form-select @error('method') is-invalid @enderror"></select>
                            @error('method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="prefix" class="text-black">URL Prefix</label>
                            <input type="text" name="prefix" id="prefix" class="form-control @error('prefix') is-invalid @enderror" placeholder="URL Prefix" value="{{ old('prefix') }}">
                            @error('prefix')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="action" class="text-black">Web URL</label>
                            <input type="text" name="action" id="action" class="form-control @error('action') is-invalid @enderror" placeholder="Web Url" value="{{ old('action') }}">
                            <small id="final_url" class="form-text text-muted"></small>
                            @error('action')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="route_name" class="text-black">Route name</label>
                            <input type="text" name="route_name" id="route_name" class="form-control @error('route_name') is-invalid @enderror" placeholder="Route name" value="{{ old('route_name') }}">
                            @error('route_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-4 col-md-4">
                            <label for="menu_status" class="text-black">Admin Menu</label>
                            <select name="menu_status" id="menu_status" class="form-control form-select @error('menu_status') is-invalid @enderror">
                                <option value="0" <?php echo (old('menu_status') == 0 || old('menu_status') == "0") ? 'selected' : ''; ?>>No</option>
                                <option value="1" <?php echo (old('menu_status') == 1 || old('menu_status') == "1") ? 'selected' : ''; ?>>Yes</option>
                            </select>
                            @error('menu_status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="menu_type" class="text-black">Menu Type</label>
                            <select name="menu_type[]" id="menu_type" class="form-control form-select @error('menu_type') is-invalid @enderror" multiple>
                                <option value="">Select Menu Type</option>
                                <?php $MenuTypeArr = $data['accessFor'];
                                foreach($MenuTypeArr as $val){ ?>
                                    <option value="<?= $val['id'] ?>" <?php echo (old('menu_type') && in_array($val['id'], old('menu_type'))) ? 'selected' : ''; ?>><?= $val['name'] ?></option>
                                <?php } ?>
                            </select>
                            @error('menu_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-4 col-md-4">
                            <label for="route_type" class="text-black">Router Type</label>
                            <select name="route_type[]" id="route_type" class="form-control form-select @error('route_type') is-invalid @enderror" multiple>
                                <?php $routesArr = ['post', 'get','put','patch','delete'];
                                foreach($routesArr as $val){ ?>
                                    <option value="<?= $val ?>" <?php echo (old('route_type') && in_array($val, old('route_type')) ) ? 'selected' : ''; ?>><?= strtoupper( $val) ?></option>
                                <?php } ?>
                            </select>
                            @error('route_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>



                        <div class="form-group col-lg-4 col-md-4">
                            <label for="menu_label" class="text-black">Admin Menu Label</label>
                            <input type="text" name="menu_label" id="menu_label" class="form-control @error('menu_label') is-invalid @enderror"
                            placeholder="Admin Menu Label" value="{{ old('menu_label') }}">
                            @error('menu_label')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="menu_sequence" class="text-black">Menu Label Sequence</label>
                            <input type="number" name="menu_sequence" id="menu_sequence" list="menu_sequence_list" class="form-control @error('menu_sequence') is-invalid @enderror"  placeholder="Menu Label Sequence" value="{{ old('menu_sequence') }}">
                            <datalist id="menu_sequence_list">
                                <?php foreach($data['menuOrder'] as $key => $val){ ?>
                                    <option value="<?= $val['menu_sequence'] ?>"><?= $val['module_label']?></option>
                                <?php } ?>
                            </datalist>
                            @error('menu_sequence')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="menu_order" class="text-black">Sub-menu Order</label>
                            <input type="number" name="menu_order" id="menu_order" class="form-control @error('menu_order') is-invalid @enderror"  placeholder="Sub-menu Order" value="{{ old('menu_order') }}">
                            @error('menu_order')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-4 col-md-4">
                            <label for="menu_icon" class="text-black">Menu Icon</label>
                            <input type="text" name="menu_icon" id="menu_icon" class="form-control @error('menu_icon') is-invalid @enderror"  placeholder="Menu Icon" value="{{ old('menu_icon') }}">
                            @error('menu_icon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="module_label" class="text-black">Module Label</label>
                            <input type="text" name="module_label" id="module_label" class="form-control @error('module_label') is-invalid @enderror"  placeholder="Module Label" value="{{ old('module_label') }}">
                            @error('module_label')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label for="extra_options" class="text-black">Extra Options</label>
                            <div class="d-flex">
                                <div class="form-check me-3 ps-4">
                                    <input class="form-check-input @error('extra_options') is-invalid @enderror" type="checkbox" value="auth" name="extra_options[]" id="auth" {{ old('extra_options') && in_array('auth', old('extra_options')) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-0" for="auth">Auth</label>
                                </div>
                            </div>
                            @error('extra_options')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-4 col-md-4">
                            <label for="status" class="text-black">Status</label>
                            <select name="status" id="status" class="form-select form-select @error('status') is-invalid @enderror">
                                <option value="1" <?php echo (old('status') == 1 || old('status') == "1") ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo (old('status') == 0 || old('status') == "0") ? 'selected' : ''; ?>>Deactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger" role="alert">You do not have permission to create a menu.</div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script> -->
<script>
    $(document).ready(function() {
        $('#controller').change(function(){
			var controller = $(this).val();
			$.ajax({
				url: "<?php echo route('useraccess.menu.methodNames'); ?>",
				type: "GET",
                data: { "controller": controller },
				dataType: "JSON",
				success: function (response) {
					$("#method").empty();
					var option = '';
                    console.log(response );
					$.each(response,function(k,v){
						var method = Object.keys(v);
						var argus = '()';
						var actionArgu = '';
						if (v[method]['num'] > 0) {
							for (var i = 0; i < v[method]['num']; i++) {
                                console.log(v[method]['argus'] );
								actionArgu += '/{'+v[method]['argus'][i]+'}';
							}
							argus = "("+v[method]['argus'].join(",")+")";
						}
						option += `<option value="${method}" data-function="${method+actionArgu}">${method}${argus}</option>`;
					})
					$("#method").append(option);
				}
			});
		})
        $("#method").change(function(){
            var actionname = "/"+$("#method option:selected").data('function').replaceAll("$","")
            $("#action").val(actionname);
            var prefix = $("#prefix").val();
            var action = $('#action').val();
            createRoute(prefix, action);
		})
        $('#prefix').on('input', function() {

            var prefix = $(this).val();
            var action = $('#action').val();
            createRoute( prefix, action);
		});
        $('#action').on('input', function() {

            var prefix = $("#prefix").val();
            var action = $('#action').val();
            createRoute(prefix, action);
		});
        $('#name, #menu_label').on('input', function() {
			$('#name, #menu_label').val($(this).val());
		});
    });
    function createRoute(prefix, action){
        var routename = action.replace(/^\//, '')       // remove first slash
                            .replace(/\/\{.*?\}/g, '') // remove trailing /{id}
                            .replace(/\//g, '.')      // replace remaining "/" with "."
                            .replace(/\/\?.*?\=/g, ''); // remove trailing /{id}

        var finalroutename = prefix ? prefix + '.' + routename : routename;
        $("#route_name").val(finalroutename);
        $("#final_url").show().text("Final URL will be : "+prefix+action);
    }

</script>
@endpush
