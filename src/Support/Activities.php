<?php

namespace Zerp\Lead\Support;

use App\Models\User;
use Illuminate\Support\Carbon;
use Zerp\Lead\Models\DealTask;
use Zerp\Lead\Models\LeadTask;

/**
 * Collects a user's open (On Going) activities across leads and deals into one
 * normalized, date-sorted list. Shared by the My Activities page and the
 * dashboard widget so the ownership/filtering logic lives in one place.
 */
class Activities
{
    /**
     * @return array<int, array<string, mixed>> ordered by date ascending
     */
    public static function forUser(User $user, ?int $limit = null): array
    {
        $today = Carbon::today();

        $leadTasks = LeadTask::with('lead:id,name')
            ->where('status', 'On Going')
            ->where(function ($q) use ($user) {
                if ($user->can('manage-any-lead-tasks')) {
                    $q->where('created_by', creatorId());
                } elseif ($user->can('manage-own-lead-tasks')) {
                    $q->where(function ($subQ) use ($user) {
                        $subQ->where('creator_id', $user->id)
                            ->orWhereIn('lead_id', function ($leadQ) use ($user) {
                                $leadQ->select('lead_id')->from('user_leads')->where('user_id', $user->id);
                            });
                    });
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->get();

        $dealTasks = DealTask::with('deal:id,name')
            ->where('status', 'On Going')
            ->where(function ($q) use ($user) {
                if ($user->can('manage-any-deal-tasks')) {
                    $q->where('created_by', creatorId());
                } elseif ($user->can('manage-own-deal-tasks')) {
                    $q->where(function ($subQ) use ($user) {
                        $subQ->where('creator_id', $user->id)
                            ->orWhereIn('deal_id', function ($dealQ) use ($user) {
                                $dealQ->select('deal_id')->from('user_deals')->where('user_id', $user->id);
                            });
                    });
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->get();

        $activities = [];

        foreach ($leadTasks as $t) {
            $activities[] = self::normalize($t, 'lead', $t->lead, $today);
        }
        foreach ($dealTasks as $t) {
            $activities[] = self::normalize($t, 'deal', $t->deal, $today);
        }

        // Sort by date ascending; undated activities sink to the bottom.
        usort($activities, fn ($a, $b) => ($a['date'] ?? '9999-12-31') <=> ($b['date'] ?? '9999-12-31'));

        return $limit ? array_slice($activities, 0, $limit) : $activities;
    }

    private static function normalize($task, string $recordType, $record, Carbon $today): array
    {
        $date = $task->date ? Carbon::parse($task->date) : null;
        $state = 'planned';
        if ($date) {
            $state = $date->isBefore($today) ? 'overdue' : ($date->isSameDay($today) ? 'today' : 'planned');
        }

        return [
            'id' => $task->id,
            'type' => $task->type ?? 'todo',
            'name' => $task->name,
            'priority' => $task->priority,
            'date' => $date?->format('Y-m-d'),
            'time' => $task->time ? $task->time->format('H:i') : null,
            'record_type' => $recordType,
            'record_id' => $record?->id,
            'record_name' => $record?->name,
            'url' => $record ? route($recordType === 'lead' ? 'lead.leads.show' : 'lead.deals.show', $record->id) : null,
            'state' => $state,
        ];
    }
}
