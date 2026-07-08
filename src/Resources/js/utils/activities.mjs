// Next-activity helpers (Odoo-style scheduled-activity indicators).
//
// A record's "next activity" is the earliest dated, still-ongoing task on it.
// Its state colors the kanban dot: overdue (past), today, or planned (future).
// Pure module (no imports) so it bundles into the browser build; self-check
// lives in activities.selfcheck.mjs.

export const ACTIVITY_TYPES = { todo: 'To-Do', call: 'Call', email: 'Email', meeting: 'Meeting' };

const DONE = new Set(['Complete', 'Completed', 1, '1']);

export function activityState(dateStr, today = new Date()) {
  if (!dateStr) return 'planned';
  const d = new Date(String(dateStr).substring(0, 10) + 'T00:00:00');
  const t = new Date(today);
  t.setHours(0, 0, 0, 0);
  if (d < t) return 'overdue';
  if (d.getTime() === t.getTime()) return 'today';
  return 'planned';
}

export function nextActivity(tasks, today = new Date()) {
  const ongoing = (tasks || [])
    .filter((t) => t && t.date && !DONE.has(t.status))
    .sort((a, b) => String(a.date).localeCompare(String(b.date)));
  if (!ongoing.length) return null;
  const next = ongoing[0];
  return { date: next.date, type: next.type || 'todo', state: activityState(next.date, today) };
}
