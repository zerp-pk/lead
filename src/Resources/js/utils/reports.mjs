// Win/loss + funnel helpers for the Deal Reports page.
// Pure module (no imports) so it bundles into the browser build; self-check
// lives in reports.selfcheck.mjs.

// Win rate over CLOSED deals only. Null when nothing has closed (avoids 0/0).
export function winRate(won, lost) {
  const closed = (Number(won) || 0) + (Number(lost) || 0);
  if (closed === 0) return null;
  return Math.round(((Number(won) || 0) / closed) * 1000) / 10;
}

// Funnel bar widths as % of the largest stage, so the widest bar is 100%.
// Returns the same array with a `width` (0–100) added to each stage.
export function funnelWidths(stages) {
  const max = (stages || []).reduce((m, s) => Math.max(m, Number(s?.count) || 0), 0);
  return (stages || []).map((s) => ({
    ...s,
    width: max > 0 ? Math.round(((Number(s?.count) || 0) / max) * 100) : 0,
  }));
}
