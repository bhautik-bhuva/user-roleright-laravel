@extends('layouts.app')
@section('title', 'Menu List - Laravel')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Create Menu</h4>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('menu.save') }}" method="POST">
                @csrf
                <div class="row mt-3">
					<div class="form-group col-lg-4 col-md-4">
						<label for="" class="text-black">Name</label>
						<input type="text" name="name" id="name" class="form-control" value="" placeholder="Name">
					</div>
					<div class="form-group col-lg-4 col-md-4">
						<label for="" class="text-black">Controller Name</label>
						<select name="contoller" id="controller" class="form-control form-select">
							<option value="">Select Controller</option>
							<?php foreach ($data['controllers'] as $key => $value): ?>
								<option value="<?php echo $value ?>"><?php echo $value ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group col-lg-4 col-md-4">
						<label for="" class="text-black">Method Name</label>
						<select name="" id="methods" class="form-control form-select"></select>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-lg-6">
						<div class="row">
							<div class="form-group col-lg-12">
								<label for="" class="text-black">Web URL</label>
								<input type="text" name="action" id="action" class="form-control" value="/" placeholder="Web Url">
							</div>
							<div class="form-group col-lg-12">
								<label for="" class="text-black">Method URI Segment</label>
								<input type="text" name="method" id="uri_segment" class="form-control" value="" placeholder="URI Segment">
							</div>
						</div>
					</div>

					<div class="form-group col-lg-6">
						<label for="" class="text-black">Router Type</label>
						<select name="route_type[]" id="route_type" class="form-control form-select required" multiple>
							<!-- <option value="">Select Router Type</option> -->
							<option value="post">POST</option>
							<option value="get">GET</option>
							<option value="put">PUT</option>
							<option value="patch">PATCH</option>
							<option value="delete">DELETE</option>
						</select>
					</div>
				</div>
				<div class="row mt-3">
					<div class="form-group col-lg-4">
						<label for="" class="text-black">Menu Type</label>
						<select name="menu_type" id="menu_type" class="form-control form-select">
							<option value="">Select Menu Type</option>
							<option value="Admin">Admin</option>
							<option value="Admin Backend">Admin Backend</option>
							<option value="Front">Front</option>
							<option value="Front Backend">Front Backend</option>
							<option value="Agent">Agent</option>
							<option value="Agent Backend">Agent Backend</option>
						</select>
					</div>
					<div class="form-group col-lg-4">
						<label for="" class="text-black">Admin Menu</label>
						<select name="menu_status" id="" class="form-select">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>
					<div class="form-group col-lg-4">
						<label for="" class="text-black">Admin Menu Label</label>
						<input type="text" name="menu_label" id="menu_label" class="form-control" value=""
						placeholder="Admin Menu Label">
					</div>
				</div>
				<div class="row mt-3">
					<div class="form-group col-lg-4 col-md-4">
						<label for="" class="text-black">Menu Sequence</label>
						<input type="text" name="menu_sequence" id="menu_sequence" list="menu_sequence_list" class="form-control" value="" placeholder="Menu Sequence">
						<datalist id="menu_sequence_list">
							<?php foreach($data['menuOrder'] as $key => $val){ ?>
								<option value="<?= $val['menu_sequence'] ?>"><?= $val['module_label']?></option>
							<?php } ?>
						</datalist>
					</div>
					<div class="form-group col-lg-4 col-md-4">
						<label for="" class="text-black">Menu Order</label>
						<input type="text" name="menu_order" id="menu_order" class="form-control" value="" placeholder="Menu Order">
					</div>
					<div class="form-group col-lg-4 col-md-4">
						<label for="" class="text-black">Menu Icon</label>
						<input type="text" name="menu_icon" id="menu_icon" class="form-control" value="" placeholder="Menu Icon">
					</div>
				</div>
				<div class="row mt-3">
					<div class="form-group col-lg-4 col-md-4">
						<label for="" class="text-black">Module Label</label>
						<input type="text" name="module_label" id="module_label" class="form-control" value="" placeholder="Module Label">
					</div>
					<div class="form-group col-lg-8 col-md-8">
						<label for="" class="text-black">Extra Options</label>
						<div class="d-flex mb-2">
							<div class="form-check me-3">
								<input class="form-check-input" type="radio" name="extra_options[type]" id="front" value="front">
								<label class="form-check-label" for="front"> Front </label>
							</div>
							<div class="form-check me-3">
								<input class="form-check-input" type="radio" name="extra_options[type]" id="admin" value="admin">
								<label class="form-check-label" for="admin"> Admin </label>
							</div>
							<div class="form-check me-3">
								<input class="form-check-input" type="radio" name="extra_options[type]" id="agent" value="agent">
								<label class="form-check-label" for="agent"> Agent </label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="extra_options[type]" id="open" value="open">
								<label class="form-check-label" for="open"> Open </label>
							</div>
						</div>
						<div class="d-flex">
							<div class="form-check me-3">
								<input class="form-check-input" type="checkbox" value="loginGuard" name="extra_options[filter][]" id="loginguard">
								<label class="form-check-label" for="loginguard"> Login Guard </label>
							</div>
							<div class="form-check me-3">
								<input class="form-check-input" type="checkbox" value="AdminGuard" name="extra_options[filter][]" id="AdminGuard">
								<label class="form-check-label" for="AdminGuard"> Admin Guard </label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="AgentGuard" name="extra_options[filter][]" id="AgentGuard">
								<label class="form-check-label" for="AgentGuard"> Agent Guard </label>
							</div>
						</div>
					</div>

					<div class="form-group col-lg-2 mt-3">
						<label for="" class="text-black">Status</label>
						<select name="status" id="active_status" class="form-select form-select">
							<option value="1">Active</option>
							<option value="0">Deactive</option>
						</select>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-6">
                        <input type="submit" class="btn btn-primary" name="submit" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#menuTable').DataTable( );
    });
</script>
@endpush
