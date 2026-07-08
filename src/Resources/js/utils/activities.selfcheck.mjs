// Self-check for activities.mjs. Node-only, never imported by the app.
// Run: `node activities.selfcheck.mjs` (exits non-zero on failure).
import assert from 'node:assert/strict';
import { activityState, nextActivity } from './activities.mjs';

const today = new Date('2026-07-08T12:00:00');

// state boundaries
assert.equal(activityState('2026-07-01', today), 'overdue');
assert.equal(activityState('2026-07-08', today), 'today');
assert.equal(activityState('2026-07-20', today), 'planned');
assert.equal(activityState(null, today), 'planned');
// ISO datetime strings (Laravel date cast) are handled
assert.equal(activityState('2026-07-08T00:00:00.000000Z', today), 'today');

// nextActivity picks earliest ongoing dated task, skips done + undated
const tasks = [
  { date: '2026-07-20', status: 'On Going', type: 'call' },
  { date: '2026-07-01', status: 'Complete', type: 'email' }, // done → ignored
  { date: '2026-07-10', status: 'On Going', type: 'meeting' },
  { date: null, status: 'On Going', type: 'todo' }, // undated → ignored
];
const n = nextActivity(tasks, today);
assert.equal(n.date, '2026-07-10');
assert.equal(n.type, 'meeting');
assert.equal(n.state, 'planned');

// no ongoing → null
assert.equal(nextActivity([{ date: '2026-07-01', status: 'Complete' }], today), null);
assert.equal(nextActivity([], today), null);

console.log('activities.mjs self-check passed');
