<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Zerp\Lead\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealFile extends Model
{
    use TenantScoped;

    /** No created_by column; the parent deal carries the tenant boundary. */
    public string $tenantParent = 'deal';

    protected $fillable = [
        'deal_id',
        'file_name',
        'file_path',
        'media_id',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(\Spatie\MediaLibrary\MediaCollections\Models\Media::class, 'media_id');
    }
}