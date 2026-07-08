@extends('laravelMain::' . $layout_file)

@section('title', 'Useraccess Panel')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->
@endpush
@section('content')
@if(session('success'))
<div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4>Change Useraccess Settings</h4>
                <hr>
                <form action="{{ route('useraccess.update.setting') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group">
                            <label for="nameuser_table" class="text-black">User Table</label>
                            <input type="text" name="user_table" id="user_table" class="form-control @error('user_table') is-invalid @enderror" placeholder="User Table" value="{{ old('user_table',  $content['user_table'])  }}">
                            @error('user_table')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            @php $layout_path = str_replace(str_replace('\\', '/', base_path()). "/" , '', str_replace('\\', '/', $content['layout_path'])) @endphp
                            <label for="layout_path" class="text-black">Layout Path</label>
                            <input type="text" name="layout_path" id="layout_path" class="form-control @error('layout_path') is-invalid @enderror" placeholder="Layout Path" value="{{ old('layout_path', $layout_path) }}">
                            @error('layout_path')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="yield_container" class="text-black">Yield container</label>
                            <input type="text" name="yield_container" id="yield_container" class="form-control @error('yield_container') is-invalid @enderror"
                                placeholder="Yield container" value="{{ old('yield_container', $content['yield_container']) }}">
                            @error('yield_container')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-sm mb-0">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mt-1">
            <div class="card-body">
                <h4>File Explaination</h4>
                <hr>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0"><strong>routes/UserAccessDynamicRoutes.php</strong> - Dynamically generates routes from the <code>module_action</code> table based on user access permissions. <br>After migrating current routes, comment/remove routes that specify both a controller and a method from this file. If you will not comment/remove routes from this file, then this will overrides your current routes.</li>
                    <li class="list-group-item px-0"><strong>public/assets/vendor/useraccess/hierarchical/images/</strong> - Used to store package assets.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4>Menu Structure</h4>
                <hr>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0"><strong>icon_class</strong> - Used for icon in menu</li>
                    <li class="list-group-item px-0"><strong>module_label</strong> - Used for Main menu</li>
                    <li class="list-group-item px-0"><strong>menu_sequence</strong> - Used for sequence of Main menu</li>
                    <li class="list-group-item px-0"><strong>menu_label</strong> - Used for menu label located under main menu</li>
                    <li class="list-group-item px-0"><strong>menu_order</strong> - Used for sequence of sub menu</li>
                    <li class="list-group-item px-0"><strong>menu_type</strong> - Contain Role access or user access</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4>Usefull Links</h4>
                <hr>
                <div>
                    <h4>Useraccess plugin Links</h4>
                    @if (Route::has('useraccess.menu.list'))
                    <a class="btn btn-primary btn-sm mb-0" href="{{ route('useraccess.menu.list') }}">Menu</a>
                    @endif
                    @if (Route::has('useraccess.role.list'))
                    <a class="btn btn-primary btn-sm mb-0" href="{{ route('useraccess.role.list') }}">Roles</a>
                    @endif
                    @if (Route::has('useraccess.user.list'))
                    <a class="btn btn-primary btn-sm mb-0" href="{{ route('useraccess.user.list') }}">Users</a>
                    @endif
                </div>
                <div class="mt-3">
                    <h4>Migrate Routes Links / Menu Json</h4>

                    @php /* $disabledclass = ""; @endphp
                    @if ($content['menu_migrated'] == 'yes')
                        @php $disabledclass = "disabled"; @endphp
                    @endif @php */  @endphp
                    @if (Route::has('useraccess.menu.json'))
                        <a class="btn btn-info btn-sm mb-0" target="_blank" href="{{ route('useraccess.menu.json') }}">Menu Json</a>
                    @endif
                    @if (Route::has('useraccess.menu.migrate'))
                        <a class="btn btn-info btn-sm mb-0 " href="{{ route('useraccess.menu.migrate') }}">Migrate Current Routes</a>
                    @endif
                </div>
                <small class="d-block mt-2 text-danger">Note: This package will migrate routes which have both a controller and a method. The route, which direct returns a view will not be migrated.</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mt-1">
            <div class="card-body">
                <h4>Available Commands</h4>
                <hr>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0"><code class="p-0">composer update techaxion/user-roleright-laravel</code> - To update package</li>
                    <li class="list-group-item px-0"><code class="p-0">composer run remove-useraccess</code>/<code>php artisan useraccess:remove</code> - To remove package from project</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">
        <label for="html_content" class="form-label">HTML Content</label>
        <textarea class="language-php form-control" name="html_content" rows="30" id="html_content">
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
        theme: "monokai" // Changed theme to "eclpse"
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
