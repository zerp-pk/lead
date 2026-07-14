<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Source extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'name',
        'creator_id',
        'created_by',
    ];

}