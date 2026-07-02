<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Source;
use Illuminate\Foundation\Events\Dispatchable;

class DestroySource
{
    use Dispatchable;

    public function __construct(
        public Source $source
    ) {}
}