// Weighted pipeline forecast helpers (Odoo-style).
//
//   stage weighted forecast = Σ( record.price × stage.probability / 100 )
//   stage revenue           = Σ( record.price )
//
// Probability is stage-driven (0–100). Records with a null/blank price count 0.
// Pure module (no imports) so it bundles cleanly into the browser build.
// Self-check lives in forecast.selfcheck.mjs — run `node forecast.selfcheck.mjs`.

export function stageRevenue(records) {
  return (records || []).reduce((sum, r) => sum + (Number(r?.price) || 0), 0);
}

export function stageForecast(records, probability) {
  const p = Number(probability) || 0;
  return (records || []).reduce((sum, r) => sum + (Number(r?.price) || 0) * p / 100, 0);
}
