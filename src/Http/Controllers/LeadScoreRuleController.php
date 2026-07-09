<?php

namespace Zerp\Lead\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Zerp\Lead\Models\LeadScoreRule;
use Zerp\Lead\Models\Source;
use Zerp\Lead\Support\LeadScoring;

// Gated under lead permissions (no dedicated permission rows).
class LeadScoreRuleController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage-leads')) {
            return back()->with('error', __('Permission denied'));
        }

        return Inertia::render('Lead/SystemSetup/LeadScoring/Index', [
            'rules' => LeadScoreRule::where('created_by', creatorId())
                ->orderByDesc('is_active')->latest()
                ->get(['id', 'name', 'field', 'operator', 'value', 'points', 'is_active']),
            'fields' => LeadScoring::FIELDS,
            'operators' => LeadScoring::OPERATORS,
            'sources' => Source::where('created_by', creatorId())->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create-leads')) {
            return back()->with('error', __('Permission denied'));
        }
        $validated = $this->validateRule($request);

        LeadScoreRule::create($validated + [
            'creator_id' => Auth::id(),
            'created_by' => creatorId(),
        ]);

        LeadScoring::recomputeAll(creatorId());

        return redirect()->route('lead.score-rules.index')->with('success', __('The scoring rule has been created successfully.'));
    }

    public function update(Request $request, LeadScoreRule $scoreRule)
    {
        if (!Auth::user()->can('edit-leads') || $scoreRule->created_by != creatorId()) {
            return back()->with('error', __('Permission denied'));
        }
        $validated = $this->validateRule($request);

        $scoreRule->update($validated);

        LeadScoring::recomputeAll(creatorId());

        return back()->with('success', __('The scoring rule has been updated successfully.'));
    }

    public function destroy(LeadScoreRule $scoreRule)
    {
        if (!Auth::user()->can('delete-leads') || $scoreRule->created_by != creatorId()) {
            return back()->with('error', __('Permission denied'));
        }

        $scoreRule->delete();
        LeadScoring::recomputeAll(creatorId());

        return back()->with('success', __('The scoring rule has been deleted.'));
    }

    private function validateRule(Request $request): array
    {
        $fields = array_column(LeadScoring::FIELDS, 'key');
        $operators = array_keys(LeadScoring::OPERATORS);

        return $request->validate([
            'name' => 'required|string|max:100',
            'field' => 'required|in:' . implode(',', $fields),
            'operator' => 'required|in:' . implode(',', $operators),
            'value' => 'nullable|string|max:100',
            'points' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
        ]);
    }
}
