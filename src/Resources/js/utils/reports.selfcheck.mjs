// Self-check for reports.mjs. Node-only, never imported by the app.
// Run: `node reports.selfcheck.mjs` (exits non-zero on failure).
import assert from 'node:assert/strict';
import { winRate, funnelWidths } from './reports.mjs';

// win rate
assert.equal(winRate(3, 1), 75);
assert.equal(winRate(0, 0), null); // nothing closed
assert.equal(winRate(1, 2), 33.3); // rounded to 1 dp
assert.equal(winRate(5, 0), 100);

// funnel widths relative to largest stage
const w = funnelWidths([{ count: 20 }, { count: 10 }, { count: 5 }]);
assert.equal(w[0].width, 100);
assert.equal(w[1].width, 50);
assert.equal(w[2].width, 25);
// all-zero stages don't divide by zero
assert.equal(funnelWidths([{ count: 0 }, { count: 0 }])[0].width, 0);
assert.deepEqual(funnelWidths([]), []);

console.log('reports.mjs self-check passed');
