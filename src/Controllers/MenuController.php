<?php

namespace Techaxion\UserAccess\Controllers;
// session_start();
use App\Http\Controllers\Controller;
use Techaxion\UserAccess\Models\ModuleAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class MenuController extends Controller
{
    public function create(Request $request)
    {
        $data['controllers'] = $this->controllersName();
		$data['menuOrder'] = ModuleAction::where('menu_status','1')->orderBy('menu_sequence', 'ASC')->get()->toArray();
        // $guards = config('auth.guards');

        // p($guards);
        return view('useraccess::menu.create', compact('data'));
    }
    public function save(Request $request)
    {
        p( $request->all());
        return view('useraccess::menu.create');
    }
    public function edit(Request $request, $id)
    {
        // Your logic to edit a role
        return response()->json(['message' => 'Role edited successfully']);
    }

    public function index()
    {
        $menu = ModuleAction::get()->toArray();
        // p($menu);
        return view('useraccess::menu.index', ['menus' => $menu]);
    }

    public function datatable(Request $request)
    {
        // Your logic to get menu for datatable
        return response()->json(['message' => 'Menu for datatable']);
    }
    public function getDirContents($dir, &$results = array()) {
		$files = scandir($dir);
		foreach ($files as $key => $value) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				// $results[] = ltrim(str_replace([APPPATH.'Controllers/',APPPATH.'Controllers',".php"],"",$path),"\\");
				$path = str_replace([app_path('Http')."\Controllers\\", app_path('Http')."\Controllers", ".php"], "", $path);
				$results[] = ltrim(str_replace('/',"\\",$path),"\\");
			} else if ($value != "." && $value != "..") {
				$this->getDirContents($path, $results);
				// $results[] = $value;
			}
		}
		return $results;
	}
    public function controllersName()
	{
		$Controllers = $this->getDirContents(app_path('Http') );
		$Controllers = array_diff($Controllers, array("BaseController",'..', '.'));
		$Controllers = array_map(function($value){ return str_replace(".php","",$value); }, $Controllers);
		return array_values($Controllers);
	}
}
