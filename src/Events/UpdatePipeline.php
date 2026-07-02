<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Pipeline;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class UpdatePipeline
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Pipeline $pipeline
    ) {}
}