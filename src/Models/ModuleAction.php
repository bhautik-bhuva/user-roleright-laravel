<?php

namespace Techaxion\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleAction extends Model
{
    use HasFactory;

    protected $table = 'module_actions';
    protected $primaryKey = 'id';    

    public function getAllActions(){
        $dataAdmin = ModuleAction::where('status',1)
        ->whereIn("menu_type",["Admin","Admin Backend"])
        ->orderby('menu_sequence', 'ASC')
        ->orderby('menu_order', 'ASC')
        ->get()->toArray();
        $newArray = array();
        foreach ($dataAdmin as $key => $value) {
            $newArray[$value['module_label']][]  =  $value;
        }
        return $newArray;
    }
}
