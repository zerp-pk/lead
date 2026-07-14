<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\TenantScoped;
use App\Models\User;

class DealCall extends Model
{
    use TenantScoped;

    /** No created_by column; the parent deal carries the tenant boundary. */
    public string $tenantParent = 'deal';

    protected $fillable = [
        'deal_id',
        'subject',
        'call_type',
        'duration',
        'user_id',
        'description',
        'call_result',
    ];

    protected $casts = [
        'deal_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}