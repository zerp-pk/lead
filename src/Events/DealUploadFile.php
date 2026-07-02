<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Deal;
use Zerp\Lead\Models\DealFile;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class DealUploadFile
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Deal $deal,
    ) {}
}