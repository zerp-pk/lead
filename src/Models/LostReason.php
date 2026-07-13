<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Zerp\Lead\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LostReason extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'name',
        'creator_id',
        'created_by',
    ];
}
