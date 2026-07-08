// Self-check for forecast.mjs. Node-only, never imported by the app.
// Run: `node forecast.selfcheck.mjs` (exits non-zero on failure).
import assert from 'node:assert/strict';
import { stageRevenue, stageForecast } from './forecast.mjs';

const recs = [{ price: 1000 }, { price: 500 }, { price: null }, {}];
// revenue ignores null/missing price
assert.equal(stageRevenue(recs), 1500);
// weighted: (1000+500) * 40/100 = 600
assert.equal(stageForecast(recs, 40), 600);
// empty column and zero probability both yield 0
assert.equal(stageForecast([], 40), 0);
assert.equal(stageForecast(recs, 0), 0);
assert.equal(stageRevenue([]), 0);
// string price (JSON decimal) is coerced
assert.equal(stageForecast([{ price: '200.00' }], 50), 100);

console.log('forecast.mjs self-check passed');
