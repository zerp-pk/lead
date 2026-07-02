<?php

namespace Zerp\Lead\Events;

use Zerp\Lead\Models\Lead;
use Zerp\Lead\Models\LeadFile;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class LeadUploadFile
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Lead $lead,
    ) {}
}