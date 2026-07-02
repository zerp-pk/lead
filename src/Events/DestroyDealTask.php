<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\DealTask;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyDealTask
{
    use Dispatchable;

    public function __construct(
        public DealTask $dealTask
    ) {}
}