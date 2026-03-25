/** Turvaline HTML Leafleti popup’ile (marker.name/description võivad sisaldada erimärke). */
export function escapeHtml(text: string): string {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

export type MarkerPopupFields = {
    id: number;
    name: string;
    latitude: number;
    longitude: number;
    description: string | null;
};

/**
 * Popup sisu: nimi, täpsed koordinaadid, kirjeldus, link täisvaatesse.
 * Inline-stiilid, sest Leaflet lisab DOM-i väljaspool Vue/Tailwind konteksti.
 */
export function buildMarkerPopupHtml(m: MarkerPopupFields): string {
    const lat = Number(m.latitude).toFixed(7);
    const lng = Number(m.longitude).toFixed(7);
    const descBlock = m.description
        ? `<p style="margin:8px 0 0;font-size:12px;line-height:1.45;white-space:pre-wrap;word-break:break-word;">${escapeHtml(m.description)}</p>`
        : '';
    return `
<div style="min-width:210px;max-width:min(92vw,300px);">
  <strong style="font-size:14px;display:block;">${escapeHtml(m.name)}</strong>
  <p style="margin:6px 0 0;font-size:11px;font-family:ui-monospace,monospace;opacity:.9;">${escapeHtml(lat)}, ${escapeHtml(lng)}</p>
  ${descBlock}
  <p style="margin:10px 0 0;font-size:12px;">
    <a href="/markers/${m.id}" style="color:#047857;font-weight:600;">Täielik vaade →</a>
  </p>
</div>`.trim();
}
