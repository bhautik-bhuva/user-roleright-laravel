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
}
