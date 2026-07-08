<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadScoreRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'field',
        'operator',
        'value',
        'points',
        'is_active',
        'creator_id',
        'created_by',
    ];

    protected $casts = [
        'points' => 'integer',
        'is_active' => 'boolean',
    ];
}
