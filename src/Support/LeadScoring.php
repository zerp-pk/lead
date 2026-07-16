<?php

namespace Zerp\Lead\Support;

use Zerp\Lead\Models\Lead;
use Zerp\Lead\Models\LeadScoreRule;

/**
 * Configurable, rule-based lead scoring.
 *
 * Admins define rules (field + operator + value + points). A lead's score is the
 * sum of matched rule points, normalized to 0–100 against the maximum achievable
 * (sum of all active rule points). Signals include both lead attributes and
 * engagement (activity/call counts), so the stored score must be recomputed
 * whenever any of those change - see recompute()/recomputeAll() call sites.
 */
class LeadScoring
{
    // Signal fields exposed to the rule builder. type drives the UI input.
    public const FIELDS = [
        ['key' => 'email_present',        'label' => 'Email is set',            'type' => 'bool'],
        ['key' => 'phone_present',        'label' => 'Phone is set',            'type' => 'bool'],
        ['key' => 'has_expected_revenue', 'label' => 'Has expected revenue',    'type' => 'bool'],
        ['key' => 'expected_revenue',     'label' => 'Expected revenue',        'type' => 'numeric'],
        ['key' => 'has_close_date',       'label' => 'Has expected close date', 'type' => 'bool'],
        ['key' => 'source',               'label' => 'Source',                  'type' => 'source'],
        ['key' => 'activity_count',       'label' => 'Activity count',          'type' => 'numeric'],
        ['key' => 'call_count',           'label' => 'Call count',              'type' => 'numeric'],
    ];

    public const OPERATORS = [
        'is_set' => 'is set',
        'equals' => 'equals',
        'gte'    => 'at least (>=)',
        'lte'    => 'at most (<=)',
    ];

    /**
     * Pure evaluator. $signals: [field => scalar|array]. $rules: array of
     * ['field','operator','value','points']. Returns total/max/percent.
     * No DB, no models - this is the unit under test.
     */
    public static function evaluate(array $signals, array $rules): array
    {
        $total = 0;
        $max = 0;
        foreach ($rules as $rule) {
            $points = (int) ($rule['points'] ?? 0);
            $max += $points;
            $value = $signals[$rule['field']] ?? 0;
            if (self::matches($value, $rule['operator'] ?? 'is_set', $rule['value'] ?? null)) {
                $total += $points;
            }
        }
        $percent = $max > 0 ? (int) round($total / $max * 100) : 0;

        return ['total' => $total, 'max' => $max, 'percent' => max(0, min(100, $percent))];
    }

    private static function matches($signalValue, string $operator, $ruleValue): bool
    {
        switch ($operator) {
            case 'is_set':
                if (is_array($signalValue)) {
                    return count($signalValue) > 0;
                }
                return !empty($signalValue);
            case 'equals':
                if (is_array($signalValue)) {
                    return in_array((string) $ruleValue, array_map('strval', $signalValue), true);
                }
                return (string) $signalValue === (string) $ruleValue;
            case 'gte':
                return (float) $signalValue >= (float) $ruleValue;
            case 'lte':
                return (float) $signalValue <= (float) $ruleValue;
            default:
                return false;
        }
    }

    /** Build the signal map for a lead (attributes + engagement counts). */
    public static function signals(Lead $lead): array
    {
        $sources = [];
        if (!empty($lead->sources)) {
            $sources = is_array($lead->sources)
                ? $lead->sources
                : array_filter(explode(',', (string) $lead->sources));
        }

        return [
            'email_present'        => !empty($lead->email) ? 1 : 0,
            'phone_present'        => !empty($lead->phone) ? 1 : 0,
            'has_expected_revenue' => ($lead->price > 0) ? 1 : 0,
            'expected_revenue'     => (float) ($lead->price ?? 0),
            'has_close_date'       => !empty($lead->expected_close_date) ? 1 : 0,
            'source'               => array_values($sources),
            'activity_count'       => $lead->tasks()->count(),
            'call_count'           => $lead->calls()->count(),
        ];
    }

    /** Recompute and persist one lead's score. Safe to call after any change. */
    public static function recompute(Lead $lead): void
    {
        $rules = self::activeRules((int) $lead->created_by);
        $result = self::evaluate(self::signals($lead), $rules);
        // Avoid firing model events / touching timestamps for a derived field.
        Lead::withoutEvents(fn () => $lead->newQuery()->whereKey($lead->id)->update(['score' => $result['percent']]));
        $lead->score = $result['percent'];
    }

    /**
     * Recompute every lead for a creator. Triggered when rules change.
     * ponytail: O(leads) with per-lead count queries; fine at SMB scale - move
     * to a queued job with grouped counts if lead volume grows large.
     */
    public static function recomputeAll(int $creatorId): void
    {
        $rules = self::activeRules($creatorId);
        Lead::where('created_by', $creatorId)->chunkById(200, function ($leads) use ($rules) {
            foreach ($leads as $lead) {
                $result = self::evaluate(self::signals($lead), $rules);
                Lead::withoutEvents(fn () => $lead->newQuery()->whereKey($lead->id)->update(['score' => $result['percent']]));
            }
        });
    }

    private static function activeRules(int $creatorId): array
    {
        return LeadScoreRule::where('created_by', $creatorId)
            ->where('is_active', true)
            ->get(['field', 'operator', 'value', 'points'])
            ->map(fn ($r) => $r->toArray())
            ->all();
    }
}
