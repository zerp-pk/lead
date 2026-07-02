<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Source;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class CreateSource
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Source $source
    ) {}
}