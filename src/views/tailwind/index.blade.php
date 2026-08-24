@extends('laravelMain::' . $layout_file)

@section('title', 'Useraccess Panel')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
@endpush

@section('content')
@if(session('success'))
<div class="my-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md" role="alert">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Change Useraccess Settings Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Change Useraccess Settings</h4>
        <hr class="border-gray-200 dark:border-gray-700 mb-4">
        <form action="{{ route('useraccess.update.setting') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="user_table" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User Table</label>
                <input type="text" name="user_table" id="user_table" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('user_table') border-red-500 @enderror" 
                    placeholder="User Table" value="{{ old('user_table',  $content['user_table'])  }}">
                @error('user_table')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                @php $layout_path = str_replace(str_replace('\\', '/', base_path()). "/" , '', str_replace('\\', '/', $content['layout_path'])) @endphp
                <label for="layout_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Layout Path</label>
                <input type="text" name="layout_path" id="layout_path" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('layout_path') border-red-500 @enderror" 
                    placeholder="Layout Path" value="{{ old('layout_path', $layout_path) }}">
                @error('layout_path')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="yield_container" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Yield container</label>
                <input type="text" name="yield_container" id="yield_container" 
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('yield_container') border-red-500 @enderror"
                    placeholder="Yield container" value="{{ old('yield_container', $content['yield_container']) }}">
                @error('yield_container')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Update</button>
            </div>
        </form>
    </div>

    <!-- File Explanation Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">File Explanation</h4>
        <hr class="border-gray-200 dark:border-gray-700 mb-4">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-400">
            <li class="py-3">
                <strong class="text-gray-950 dark:text-gray-200">routes/UserAccessDynamicRoutes.php</strong> - Dynamically generates routes from the <code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">module_action</code> table based on user access permissions. <br>After migrating current routes, comment/remove routes that specify both a controller and a method from this file. If you do not comment/remove routes from this file, then this will override your current routes.
            </li>
            <li class="py-3">
                <strong class="text-gray-950 dark:text-gray-200">public/assets/vendor/useraccess/hierarchical/images/</strong> - Used to store package assets.
            </li>
        </ul>
    </div>

    <!-- Menu Structure Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Menu Structure</h4>
        <hr class="border-gray-200 dark:border-gray-700 mb-4">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-400">
            <li class="py-2"><strong class="text-gray-950 dark:text-gray-200">icon_class</strong> - Used for icon in menu</li>
            <li class="py-2"><strong class="text-gray-950 dark:text-gray-200">module_label</strong> - Used for Main menu</li>
            <li class="py-2"><strong class="text-gray-950 dark:text-gray-200">menu_sequence</strong> - Used for sequence of Main menu</li>
            <li class="py-2"><strong class="text-gray-950 dark:text-gray-200">menu_label</strong> - Used for menu label located under main menu</li>
            <li class="py-2"><strong class="text-gray-950 dark:text-gray-200">menu_order</strong> - Used for sequence of sub menu</li>
            <li class="py-2"><strong class="text-gray-950 dark:text-gray-200">menu_type</strong> - Contains Role access or user access</li>
        </ul>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <!-- Useful Links Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Useful Links</h4>
        <hr class="border-gray-200 dark:border-gray-700 mb-4">
        
        <div class="space-y-4">
            <div>
                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Useraccess plugin Links</h5>
                <div class="flex flex-wrap gap-2">
                    @if (Route::has('useraccess.interface-access.list'))
                    <a class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs hover:bg-indigo-700 transition" href="{{ route('useraccess.interface-access.list') }}">Access For</a>
                    @endif
                    @if (Route::has('useraccess.menu.list'))
                    <a class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs hover:bg-indigo-700 transition" href="{{ route('useraccess.menu.list') }}">Menu</a>
                    @endif
                    @if (Route::has('useraccess.role.list'))
                    <a class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs hover:bg-indigo-700 transition" href="{{ route('useraccess.role.list') }}">Roles</a>
                    @endif
                    @if (Route::has('useraccess.user.list'))
                    <a class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs hover:bg-indigo-700 transition" href="{{ route('useraccess.user.list') }}">Users</a>
                    @endif
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Migrate Routes Links / Menu Json</h5>
                <div class="flex flex-wrap gap-2">
                    @if (Route::has('useraccess.menu.json'))
                        <a class="px-3 py-1.5 bg-cyan-600 text-white rounded-md text-xs hover:bg-cyan-700 transition" target="_blank" href="{{ route('useraccess.menu.json') }}">Menu Json</a>
                    @endif
                    @if (Route::has('useraccess.menu.migrate'))
                        <a class="px-3 py-1.5 bg-cyan-600 text-white rounded-md text-xs hover:bg-cyan-700 transition" href="{{ route('useraccess.menu.migrate') }}">Migrate Current Routes</a>
                    @endif
                </div>
            </div>
            <p class="text-xs text-red-500 mt-2">Note: This package will migrate routes which have both a controller and a method. The route, which directly returns a view, will not be migrated.</p>
        </div>
    </div>

    <!-- Available Commands Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Available Commands</h4>
        <hr class="border-gray-200 dark:border-gray-700 mb-4">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-400">
            <li class="py-3"><p class="mb-3">- To update package</p><code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded text-indigo-600 dark:text-indigo-400">composer update techaxion/user-roleright-laravel</code> </li>
            <li class="py-3"><p class="mb-3">- To remove package from project</p><code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded text-indigo-600 dark:text-indigo-400 mb-3">composer run remove-useraccess</code> or <br><code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded text-indigo-600 dark:text-indigo-400">php artisan useraccess:remove</code></li>
        </ul>
    </div>
</div>

<div class="mt-6">
    <label for="html_content" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">HTML Content</label>
    <textarea class="language-php w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" name="html_content" rows="30" id="html_content">
        @php echo '@if (class_exists(\Techaxion\UserAccess\Controllers\SettingController::class))';
            echo '@ php
            $menujson = new Techaxion\UserAccess\Controllers\SettingController();
            $menu_list = json_decode($menujson->useraccessmenujson(), true);
            @ endphp';

            echo '
            <ul class="navbar-nav px-3">
            @foreach ($menu_list as $menukey => $menuvalue)
                @ php $newMenuValue = array_values($menuvalue); @ endphp
                @ php $extra_options = json_decode($newMenuValue[0]["extra_options"],1); @ endphp
                @ php $route_name= $extra_options["route_name"] ?? ""; @ endphp
                @ php $prefix= $extra_options["prefix"] ?? ""; @ endphp
                @if (count($newMenuValue) == 1 && $newMenuValue[0]["menu_order"] == "0")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url($prefix.$newMenuValue[0]["action"]) }}" >
                            @if (isset($newMenuValue[0]["menu_icon"] ))
                                <i class="{{ $newMenuValue[0]["menu_icon"] }}"></i>
                            @else
                                <i class="fas fa-tools me-2"></i>
                            @endif
                            <span class="menu-title">{{ $newMenuValue[0]["module_label"] }}</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-target="#topnav-{{ str_replace(" ", "_", $newMenuValue[0]["module_label"]) }}" role="button" data-bs-toggle="collapse" aria-expanded="false">
                            @if (isset($newMenuValue[0]["menu_icon"] ))
                                <i class="{{ $newMenuValue[0]["menu_icon"] }}"></i>
                            @elseif (isset($route_name ) && str_contains($route_name,"role"))
                                <i class="fas fa-lock me-2"></i>
                            @elseif (isset($route_name ) && str_contains($route_name, "user"))
                                <i class="fas fa-users me-2"></i>
                            @elseif (isset($route_name ) && str_contains($route_name, "menu"))
                                <i class="fas fa-list me-2"></i>
                            @else
                                <i class="fas fa-tools me-2"></i>
                            @endif
                            <span class="menu-title">{{ $newMenuValue[0]["module_label"] }}</span>
                        </a>
                        <ul class="navbar-nav collapse text-bg-light px-3" id="topnav-{{ str_replace(" ", "_", $newMenuValue[0]["module_label"]) }}">
                            @foreach ($newMenuValue as $imkey => $imvalue)
                                @ php $extra_options_imvalue = json_decode($imvalue["extra_options"],1); @ endphp
                                @ php $prefix_imvalue = $extra_options_imvalue["prefix"] ?? ""; @ endphp
                                <li class="nav-item"><a class="nav-link" href="{{ url($prefix_imvalue.$imvalue["action"]) }}" title="{{ $imvalue["menu_label"] }}">{{ $imvalue["menu_label"] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
            </ul>';
        echo '@endif';
        @endphp
    </textarea>
</div>

@endsection

@push('scripts')
<!-- Include CodeMirror -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/monokai.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>

<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("html_content"), {
        mode: "php",
        lineWrapping: true,
        fontsize: "18px",
        lineNumbers: true,
        theme: "monokai"
    });

    let code = editor.getValue();
    code = code.replaceAll(/\@ php/g, '@php');
    code = code.replaceAll(/\@ endphp/g, '@endphp');
    editor.setValue(code);

    setTimeout(() => {
        let code1 = editor.getValue();
        code1 = code1.replaceAll(/<\?/g, '@');
        code1 = code1.replaceAll(/\?>/g, '@endphp');
        editor.setValue(code1);
    }, 100);
</script>
@endpush
