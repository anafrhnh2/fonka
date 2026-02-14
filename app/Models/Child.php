<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'age',
        'avatar',
        'current_level',
        'stars',
        'assessment_completed',
    ];

    /**
     * Each child belongs to a parent
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Each child has one assessment
     */
    public function assessment()
    {
        return $this->hasOne(Assessment::class);
    }
}
