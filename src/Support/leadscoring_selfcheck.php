<?php
// Self-check for LeadScoring::evaluate(). Standalone, no framework/DB.
// Run: `php leadscoring_selfcheck.php` (exits non-zero on failure).
require __DIR__ . '/LeadScoring.php';

use Zerp\Lead\Support\LeadScoring;

$failures = 0;
function check(string $label, $actual, $expected): void
{
    global $failures;
    if ($actual !== $expected) {
        fwrite(STDERR, "FAIL: $label — got " . var_export($actual, true) . ", expected " . var_export($expected, true) . "\n");
        $failures++;
    }
}

$rules = [
    ['field' => 'email_present',        'operator' => 'is_set', 'value' => null, 'points' => 20],
    ['field' => 'has_expected_revenue', 'operator' => 'is_set', 'value' => null, 'points' => 30],
    ['field' => 'activity_count',       'operator' => 'gte',    'value' => '3',  'points' => 25],
    ['field' => 'source',               'operator' => 'equals', 'value' => '5',  'points' => 25],
];
// max achievable = 100

// Hot lead: email + revenue + 4 activities + source 5 => all match => 100
check('all match', LeadScoring::evaluate([
    'email_present' => 1, 'has_expected_revenue' => 1, 'activity_count' => 4, 'source' => ['2', '5'],
], $rules)['percent'], 100);

// Warm: email + revenue only => 50
check('half match', LeadScoring::evaluate([
    'email_present' => 1, 'has_expected_revenue' => 1, 'activity_count' => 0, 'source' => [],
], $rules)['percent'], 50);

// gte boundary: exactly 3 activities matches
check('gte boundary', LeadScoring::evaluate(['activity_count' => 3], $rules)['percent'], 25);
// below boundary does not match
check('below gte', LeadScoring::evaluate(['activity_count' => 2], $rules)['percent'], 0);

// source membership (array) via equals
check('source member', LeadScoring::evaluate(['source' => ['5']], $rules)['percent'], 25);
check('source absent', LeadScoring::evaluate(['source' => ['1', '2']], $rules)['percent'], 0);

// no rules => 0, no divide-by-zero
check('no rules', LeadScoring::evaluate(['email_present' => 1], [])['percent'], 0);

// lte operator
$lteRule = [['field' => 'expected_revenue', 'operator' => 'lte', 'value' => '1000', 'points' => 10]];
check('lte match', LeadScoring::evaluate(['expected_revenue' => 500], $lteRule)['percent'], 100);
check('lte miss', LeadScoring::evaluate(['expected_revenue' => 5000], $lteRule)['percent'], 0);

if ($failures === 0) {
    echo "LeadScoring::evaluate self-check passed\n";
    exit(0);
}
exit(1);
