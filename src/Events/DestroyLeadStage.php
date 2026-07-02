<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\LeadStage;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyLeadStage
{
    use Dispatchable;

    public function __construct(
        public LeadStage $leadStage
    ) {}
}