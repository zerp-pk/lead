// Self-check for scoring.mjs. Node-only, never imported by the app.
// Run: `node scoring.selfcheck.mjs` (exits non-zero on failure).
import assert from 'node:assert/strict';
import { band } from './scoring.mjs';

assert.equal(band(90).label, 'Hot');
assert.equal(band(67).label, 'Hot');   // lower boundary of Hot
assert.equal(band(66).label, 'Warm');
assert.equal(band(34).label, 'Warm');  // lower boundary of Warm
assert.equal(band(33).label, 'Cold');
assert.equal(band(0).label, 'Cold');
assert.equal(band(undefined).label, 'Cold'); // missing score

console.log('scoring.mjs self-check passed');
