<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildWeakness extends Model
{
    protected $fillable = [
        'child_id',
        'game_level_id',
        'the_question',
        'item_name',
        'category',
        'wrong_count',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
    
    public function level()
    {
        return $this->belongsTo(GameLevel::class, 'game_level_id');
    }
}