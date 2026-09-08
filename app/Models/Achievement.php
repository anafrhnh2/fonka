<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $table = 'achievements';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'bg_color',
        'border_color',
        'rule_type',
        'required_value',
    ];
}