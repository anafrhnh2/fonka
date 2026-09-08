<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildAchievement extends Model
{
    protected $table = 'child_achievements';

    protected $fillable = [
        'child_id',
        'achievement_id',
        'progress',
        'is_unlocked',
        'unlocked_at',
    ];

    public function achievement()
    {
        return $this->belongsTo(Achievement::class, 'achievement_id');
    }
}