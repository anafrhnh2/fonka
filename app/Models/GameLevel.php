<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameLevel extends Model
{
    protected $fillable = [
        'level_number',
        'name',
        'image',
        'max_stars',
        'max_points'
    ];
}
