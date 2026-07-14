<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadFile extends Model
{
    use TenantScoped;

    /** No created_by column; the parent lead carries the tenant boundary. */
    public string $tenantParent = 'lead';

    protected $fillable = [
        'lead_id',
        'file_name',
        'file_path',
        'media_id',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(\Spatie\MediaLibrary\MediaCollections\Models\Media::class, 'media_id');
    }
}