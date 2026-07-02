<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\DealStage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class UpdateDealStage
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public DealStage $dealStage
    ) {}
}