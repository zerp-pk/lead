<?php

namespace Zerp\Lead\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealDiscussion extends Model
{
    use TenantScoped;

    protected $fillable = [
        'deal_id',
        'comment',
        'creator_id',
        'created_by'
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}