<?php

namespace Techaxion\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Techaxion\UserAccess\Models\RightAction;
class UserRoleMapping extends Model
{
    use HasFactory;

    protected $table = 'user_role_mapping';
    protected $fillable = [
        'user_id',
        'role_id'
    ];
    public $timestamps = false;
    public function getUserRole($userid) {
        return self::where("user_id",$userid)->pluck('role_id')->toArray();
    }
    public function getUserRightActions($user_id, $role_id) {
        return RightAction::where("user_id",$user_id)->where("role_id",$role_id)->pluck('action_id')->toArray();
    }
}
