<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Deal;
use Zerp\Lead\Models\DealFile;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyDealFile
{
    use Dispatchable;

    public function __construct(
        public Deal $deal,
    ) {}
}