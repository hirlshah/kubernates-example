<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleConfig extends Model
{
    use HasFactory;

    protected $table = 'module_config';

    protected $guarded = ['id'];

    /**
     * Check for module not exist
     */
    public static function checkForModuleNotExist($moduleName) {
        return self::where(['name' => $moduleName, 'status' => 0])->exists();
    }
}
