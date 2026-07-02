<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Deal;
use Zerp\Lead\Models\DealTask;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class CreateDealTask
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Deal $deal,
        public DealTask $dealTask
    ) {}
}