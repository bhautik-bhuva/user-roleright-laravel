<?php

namespace Techaxion\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class InterfaceAccess extends Model
{
    use HasFactory;

    protected $table = 'interface_access';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'access_type'
    ];
    public function getAccessForid($name) {
        return self::where("name",$name)->pluck('id')->toArray();
    }
}
