<?php

namespace Techaxion\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Techaxion\UserAccess\Models\InterfaceAccess;
use Illuminate\Support\Facades\Auth;
use Techaxion\UserAccess\Models\Roles;
use Techaxion\UserAccess\Models\UserRoleMapping;
class ModuleAction extends Model
{
    use HasFactory;

    protected $table = 'module_action';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [ 'name', 'controller', 'method', 'action', 'route_type', 'menu_type', 'menu_label', 'menu_status', 'menu_sequence', 'menu_order', 'menu_icon', 'module_label', 'status', 'created_date','extra_options'];
    public function getAllActions(){

        $user = Auth::user();
        $userRoleMapping = new UserRoleMapping();
        $role_id = $userRoleMapping->getUserRole($user->id);
        $menu_typeArray = Roles::where('id', $role_id)->pluck('interface_access')->toArray();

        $dataAdmin = ModuleAction::where('status',1)
        ->where(function ($query) use ($menu_typeArray) {
            foreach ($menu_typeArray as $type) {
                $query->orWhereRaw('FIND_IN_SET(?, menu_type)', [$type]);
            }
        })
        ->orderby('menu_sequence', 'ASC')
        ->orderby('menu_order', 'ASC')
        ->get()->toArray();
        $newArray = array();
        foreach ($dataAdmin as $key => $value) {
            $newArray[$value['module_label']][]  =  $value;
        }
        return $newArray;
    }
    public function interface_access(){
        return $this->hasMany(InterfaceAccess::class, 'id', 'menu_type');
    }
}
