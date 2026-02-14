<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'confuses_letters',
        'knows_basic_sounds',
        'can_rhyme',
        'can_read_simple_words',
        'suggested_start_level',
    ];

    /**
     * Each assessment belongs to a child
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
