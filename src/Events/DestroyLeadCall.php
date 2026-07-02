<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\LeadCall;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyLeadCall
{
    use Dispatchable;

    public function __construct(
        public LeadCall $leadCall
    ) {}
}