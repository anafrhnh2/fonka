<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingProgress extends Model
{
    use HasFactory;

    protected $table = 'reading_progresses';

    protected $fillable = [
        'child_id',
        'reading_module_id',
        'progress_percentage',
        'score',
        'is_completed'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'score' => 'integer',
    ];
}