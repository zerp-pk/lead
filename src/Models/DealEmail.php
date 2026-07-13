<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Zerp\Lead\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealEmail extends Model
{
    use TenantScoped;

    /** No created_by column; the parent deal carries the tenant boundary. */
    public string $tenantParent = 'deal';

    protected $fillable = [
        'deal_id',
        'to',
        'subject',
        'description',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}