<?php

namespace Zerp\Lead\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Zerp\Lead\Support\Activities;

class ActivityController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage-lead-tasks') && !Auth::user()->can('manage-deal-tasks')) {
            return back()->with('error', __('Permission denied'));
        }

        $activities = Activities::forUser(Auth::user());

        return Inertia::render('Lead/Activities/Index', [
            'activities' => $activities,
            'counts' => [
                'overdue' => collect($activities)->where('state', 'overdue')->count(),
                'today' => collect($activities)->where('state', 'today')->count(),
                'planned' => collect($activities)->where('state', 'planned')->count(),
            ],
        ]);
    }
}
