<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProgress extends Model
{
    use HasFactory;

    protected $table = 'game_progress';

    protected $fillable = [
        'child_id',
        'game_level_id',
        'stars_earned',
        'score',
        'is_completed'
    ];
}