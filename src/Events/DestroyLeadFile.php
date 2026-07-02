<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Lead;
use Zerp\Lead\Models\LeadFile;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyLeadFile
{
    use Dispatchable;

    public function __construct(
        public Lead $lead,
    ) {}
}