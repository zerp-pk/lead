<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\LeadTask;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class UpdateLeadTask
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public LeadTask $leadTask
    ) {}
}