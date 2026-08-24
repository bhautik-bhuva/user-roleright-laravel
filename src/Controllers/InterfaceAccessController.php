<?php
namespace Techaxion\UserAccess\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Techaxion\UserAccess\Models\InterfaceAccess;
use Techaxion\UserAccess\Models\ModuleAction;
use Techaxion\UserAccess\Models\Roles;

class InterfaceAccessController extends Controller
{
    private array $useraccessData;
    private const ACCESS_TYPES = ['backend', 'frontend'];

    public function __construct(){
        $this->useraccessData = USERACCESS_CONTENT;
    }
    public function list(){
        return view($this->view('index'), ['accessForItems' => InterfaceAccess::orderBy('id')->get()]);
    }
    public function create(){
        return view($this->view('create'), ['accessTypes' => self::ACCESS_TYPES]);
    }

    public function store(Request $request){
        $data = $this->validatedData($request);
        if ($data instanceof ValidatorContract) {
            return redirect('/useraccess/interface-access/create')->withErrors($data)->withInput();
        }

        InterfaceAccess::create($data);
        return redirect('/useraccess/interface-access/list')->with('success', 'Access type created successfully.');
    }

    public function edit(InterfaceAccess $accessFor){
        return view($this->view('edit'), compact('accessFor') + ['accessTypes' => self::ACCESS_TYPES]);
    }
    public function update(Request $request, InterfaceAccess $accessFor){
        $data = $this->validatedData($request, $accessFor);
        if ($data instanceof ValidatorContract) {
            return redirect('/useraccess/interface-access/edit/' . $accessFor->id)->withErrors($data)->withInput();
        }

        $accessFor->update($data);
        return redirect('/useraccess/interface-access/edit/' . $accessFor->id)->with('success', 'Access type updated successfully.');
    }

    public function delete(InterfaceAccess $accessFor){
        $id    = (string) $accessFor->id;
        $inUse = Roles::whereRaw('FIND_IN_SET(?, interface_access)', [$id])->exists() || ModuleAction::whereRaw('FIND_IN_SET(?, menu_type)', [$id])->exists();
        if ($inUse) {
            return redirect('/useraccess/interface-access/list')->with('error', 'This access type is in use by a role or menu and cannot be deleted.');
        }

        $accessFor->delete();
        return redirect('/useraccess/interface-access/list')->with('success', 'Access type deleted successfully.');
    }

    private function validatedData(Request $request, ?InterfaceAccess $accessFor = null): array | ValidatorContract
    {
        $validator = Validator::make($request->all(), [
            'name'       => ['required', 'string', 'max:50', 'unique:interface_access,name' . ($accessFor ? ',' . $accessFor->id : '')],
            'access_type' => ['nullable', 'array'], 'access_type.*' => ['in:backend,frontend']
        ]);
        if ($validator->fails()) {
            return $validator;
        }

        return ['name' => $request->input('name'), 'access_type' => implode(',', $request->input('access_type', []))];
    }

    private function view(string $name): string
    {
        return 'useraccess::' . ($this->useraccessData['frontend'] === 'tailwind' ? 'tailwind' : 'bootstrap') . '.interface-access.' . $name;
    }
}
