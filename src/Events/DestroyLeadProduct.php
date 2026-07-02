<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Lead;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyLeadProduct
{
    use Dispatchable;

    public function __construct(
        public Lead $lead,
    ) {}
}