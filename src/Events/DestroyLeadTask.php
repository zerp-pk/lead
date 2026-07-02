<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\LeadTask;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyLeadTask
{
    use Dispatchable;

    public function __construct(
        public LeadTask $leadTask
    ) {}
}