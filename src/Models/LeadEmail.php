<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadEmail extends Model
{
    use TenantScoped;

    /** No created_by column; the parent lead carries the tenant boundary. */
    public string $tenantParent = 'lead';

    protected $fillable = [
        'lead_id',
        'to',
        'subject',
        'description',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}