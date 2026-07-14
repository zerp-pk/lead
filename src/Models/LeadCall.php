<?php

namespace Zerp\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\TenantScoped;
use App\Models\User;

class LeadCall extends Model
{
    use TenantScoped;

    /** No created_by column; the parent lead carries the tenant boundary. */
    public string $tenantParent = 'lead';

    protected $fillable = [
        'lead_id',
        'subject',
        'call_type',
        'duration',
        'user_id',
        'description',
        'call_result',
    ];

    protected $casts = [
        'lead_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
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