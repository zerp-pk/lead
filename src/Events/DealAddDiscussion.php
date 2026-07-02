<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Deal;
use Zerp\Lead\Models\DealDiscussion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class DealAddDiscussion
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Deal $deal,
    ) {}
}