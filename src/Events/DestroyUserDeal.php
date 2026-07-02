<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Deal;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyUserDeal
{
    use Dispatchable;

    public function __construct(
        public Deal $deal,
    ) {}
}