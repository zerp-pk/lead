<?php

namespace Zerp\Lead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealCallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'deal_id' => 'required|exists:deals,id,created_by,' . creatorId(),
            'subject' => 'required|string|max:255',
            'call_type' => 'required|in:Outbound,Inbound',
            'duration' => 'required|string',
            'assignee' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'call_result' => 'nullable|string',
        ];
    }
}