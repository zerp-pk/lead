<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\DealStage;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyDealStage
{
    use Dispatchable;

    public function __construct(
        public DealStage $dealStage
    ) {}
}