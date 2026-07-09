<?php

namespace Zerp\Lead\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Zerp\Lead\Models\LostReason;

// Gated under deal permissions (no dedicated permission rows) since lost reasons
// are only used when marking a deal lost.
class LostReasonController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage-deals')) {
            return back()->with('error', __('Permission denied'));
        }

        return Inertia::render('Lead/SystemSetup/LostReasons/Index', [
            'lostReasons' => LostReason::where('created_by', creatorId())
                ->select('id', 'name', 'created_at')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create-deals')) {
            return back()->with('error', __('Permission denied'));
        }
        $validated = $request->validate(['name' => 'required|max:100']);

        LostReason::create([
            'name' => $validated['name'],
            'creator_id' => Auth::id(),
            'created_by' => creatorId(),
        ]);

        return redirect()->route('lead.lost-reasons.index')->with('success', __('The lost reason has been created successfully.'));
    }

    public function update(Request $request, LostReason $lostReason)
    {
        if (!Auth::user()->can('edit-deals') || $lostReason->created_by != creatorId()) {
            return back()->with('error', __('Permission denied'));
        }
        $validated = $request->validate(['name' => 'required|max:100']);

        $lostReason->update(['name' => $validated['name']]);

        return back()->with('success', __('The lost reason details are updated successfully.'));
    }

    public function destroy(LostReason $lostReason)
    {
        if (!Auth::user()->can('delete-deals') || $lostReason->created_by != creatorId()) {
            return back()->with('error', __('Permission denied'));
        }

        $lostReason->delete();

        return back()->with('success', __('The lost reason has been deleted.'));
    }
}
