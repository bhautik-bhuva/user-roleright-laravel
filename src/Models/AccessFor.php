<?php

namespace Techaxion\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AccessFor extends Model
{
    use HasFactory;

    protected $table = 'access_for';

    protected $fillable = [
        'name',
        'route_type'
    ];
    public function getAccessForid($name) {
        return self::where("name",$name)->pluck('id')->toArray();
    }
}
