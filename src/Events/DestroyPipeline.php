<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Pipeline;
use Illuminate\Foundation\Events\Dispatchable;

class DestroyPipeline
{
    use Dispatchable;

    public function __construct(
        public Pipeline $pipeline
    ) {}
}