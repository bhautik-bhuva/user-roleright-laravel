<?php

namespace Techaxion\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RightAction extends Model
{
    use HasFactory;

    protected $table = 'right_action';

    protected $fillable = [
        'user_id',
        'role_id',
        'action_id'
    ];

}
