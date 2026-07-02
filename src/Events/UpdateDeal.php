<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Deal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class UpdateDeal
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Deal $deal
    ) {}
}