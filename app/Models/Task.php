<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tasks';

    /**
     * Get default tasks.
     */
    public static function defaultTasks() {
        return [
            'Post médias sociaux',
            'Stories',
            'Objectif de message envoyé',
            'Croissance personnelle',
            'Utiliser mes produits'
        ];
    }
}
