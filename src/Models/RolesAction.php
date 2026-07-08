<?php

namespace Techaxion\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesAction extends Model
{
    use HasFactory;

    protected $table = 'role_action';

    protected $fillable = [
        'role_id',
        'action_id'
    ];
    public function deleteRoleActions($role_id) {
        return self::where("role_id",$role_id)->delete();
    }
}
