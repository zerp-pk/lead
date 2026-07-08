// Lead-score display band. Score (0–100) is computed + stored server-side by
// LeadScoring (PHP); this only maps it to a Hot/Warm/Cold label + badge classes.
// Pure module; self-check in scoring.selfcheck.mjs.

export function band(score) {
  const s = Number(score) || 0;
  if (s >= 67) return { key: 'hot', label: 'Hot', className: 'bg-red-100 text-red-700' };
  if (s >= 34) return { key: 'warm', label: 'Warm', className: 'bg-amber-100 text-amber-700' };
  return { key: 'cold', label: 'Cold', className: 'bg-slate-100 text-slate-600' };
}
