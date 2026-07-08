@extends('laravelMain::' . $layout_file)
@section('title', 'Create Menu')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Create Menu</h4>
        @if (Route::has('useraccess.menu.list'))
            <a class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition font-medium text-gray-700 dark:text-gray-200" href="{{ route('useraccess.menu.list') }}">Back</a>
        @endif
    </div>

    @if (Route::has('useraccess.menu.store'))
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('useraccess.menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(session('route_error'))
                    <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" role="alert">
                        <strong>{{ session('route_error') }}</strong>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input type="text" name="name" id="name" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror" placeholder="Name" value="{{ old('name') }}" >
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="controller" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Controller Name</label>
                        <select name="controller" id="controller" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('controller') border-red-500 @enderror">
                            <option value="">Select Controller</option>
                            <?php foreach ($data['controllers'] as $key => $value): ?>
                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php endforeach ?>
                        </select>
                        @error('controller')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Method Name</label>
                        <select name="method" id="method" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('method') border-red-500 @enderror"></select>
                        @error('method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prefix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL Prefix</label>
                        <input type="text" name="prefix" id="prefix" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('prefix') border-red-500 @enderror" placeholder="URL Prefix" value="{{ old('prefix') }}">
                        @error('prefix')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Web URL</label>
                        <input type="text" name="action" id="action" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('action') border-red-500 @enderror" placeholder="Web Url" value="{{ old('action') }}">
                        <p id="final_url" class="text-xs text-gray-500 mt-1"></p>
                        @error('action')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="route_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Route name</label>
                        <input type="text" name="route_name" id="route_name" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('route_name') border-red-500 @enderror" placeholder="Route name" value="{{ old('route_name') }}">
                        @error('route_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="menu_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin Menu</label>
                        <select name="menu_status" id="menu_status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('menu_status') border-red-500 @enderror">
                            <option value="0" <?php echo (old('menu_status') == 0 || old('menu_status') == "0") ? 'selected' : ''; ?>>No</option>
                            <option value="1" <?php echo (old('menu_status') == 1 || old('menu_status') == "1") ? 'selected' : ''; ?>>Yes</option>
                        </select>
                        @error('menu_status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="menu_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Menu Type</label>
                        <select name="menu_type[]" id="menu_type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('menu_type') border-red-500 @enderror" multiple>
                            <option value="">Select Menu Type</option>
                            <?php $MenuTypeArr = $data['accessFor'];
                            foreach($MenuTypeArr as $val){ ?>
                                <option value="<?= $val['id'] ?>" <?php echo (old('menu_type') && in_array($val['id'], old('menu_type'))) ? 'selected' : ''; ?>><?= $val['name'] ?></option>
                            <?php } ?>
                        </select>
                        @error('menu_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="route_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Router Type</label>
                        <select name="route_type[]" id="route_type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('route_type') border-red-500 @enderror" multiple>
                            <?php $routesArr = ['post', 'get','put','patch','delete'];
                            foreach($routesArr as $val){ ?>
                                <option value="<?= $val ?>" <?php echo (old('route_type') && in_array($val, old('route_type')) ) ? 'selected' : ''; ?>><?= strtoupper( $val) ?></option>
                            <?php } ?>
                        </select>
                        @error('route_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="menu_label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin Menu Label</label>
                        <input type="text" name="menu_label" id="menu_label" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('menu_label') border-red-500 @enderror" placeholder="Admin Menu Label" value="{{ old('menu_label') }}">
                        @error('menu_label')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="menu_sequence" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Menu Label Sequence</label>
                        <input type="number" name="menu_sequence" id="menu_sequence" list="menu_sequence_list" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('menu_sequence') border-red-500 @enderror"  placeholder="Menu Label Sequence" value="{{ old('menu_sequence') }}">
                        <datalist id="menu_sequence_list">
                            <?php foreach($data['menuOrder'] as $key => $val){ ?>
                                <option value="<?= $val['menu_sequence'] ?>"><?= $val['module_label']?></option>
                            <?php } ?>
                        </datalist>
                        @error('menu_sequence')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="menu_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sub-menu Order</label>
                        <input type="number" name="menu_order" id="menu_order" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('menu_order') border-red-500 @enderror"  placeholder="Sub-menu Order" value="{{ old('menu_order') }}">
                        @error('menu_order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="menu_icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Menu Icon</label>
                        <input type="text" name="menu_icon" id="menu_icon" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('menu_icon') border-red-500 @enderror"  placeholder="Menu Icon" value="{{ old('menu_icon') }}">
                        @error('menu_icon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="module_label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Module Label</label>
                        <input type="text" name="module_label" id="module_label" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('module_label') border-red-500 @enderror"  placeholder="Module Label" value="{{ old('module_label') }}">
                        @error('module_label')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Extra Options</label>
                        <div class="flex items-center space-x-2">
                            <input class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 @error('extra_options') border-red-500 @enderror" type="checkbox" value="auth" name="extra_options[]" id="auth" {{ old('extra_options') && in_array('auth', old('extra_options')) ? 'checked' : '' }}>
                            <label class="text-sm text-gray-700 dark:text-gray-300" for="auth">Auth</label>
                        </div>
                        @error('extra_options')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                            <option value="1" <?php echo (old('status') == 1 || old('status') == "1") ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo (old('status') == 0 || old('status') == "0") ? 'selected' : ''; ?>>Deactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition shadow-sm">Save</button>
                </div>
            </form>
        </div>
    @else
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" role="alert">You do not have permission to create a menu.</div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
