<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Zerp\Lead\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zerp\Lead\Models\Pipeline;

class LeadStage extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'name',
        'order',
        'probability',
        'pipeline_id',
        'creator_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'probability' => 'integer',
        ];
    }

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }
}