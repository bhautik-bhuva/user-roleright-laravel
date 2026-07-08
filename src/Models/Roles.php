<?php

namespace Techaxion\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Techaxion\UserAccess\Models\AccessFor;
use Techaxion\UserAccess\Models\RolesAction;
class Roles extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'access',
        'access_for',
        'description',
        'created_at',
        'updated_at',
    ];
    public function access_for(){
        return $this->hasOne(AccessFor::class, 'id', 'access_for');
    }
    // get all actions for the role
    public function actions() {
        return $this->hasMany(RolesAction::class, 'role_id', 'id');
    }
    public function getRoleActions($role_id) {
        return RolesAction::where("role_id",$role_id)->pluck('action_id')->toArray();
    }
    public function getRoleName($id) {
        return self::where("id",$id)->pluck('name')->toArray();
    }
}
