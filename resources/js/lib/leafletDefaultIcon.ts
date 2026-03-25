/**
 * Leafleti vaikimisi markeri pildid tuleb bundleri kaudu laadida — muidu otsib brauser
 * neid valest teest (nt /build/assets/…), mis tootmises annab 404.
 */
import L from 'leaflet';
import iconRetina from 'leaflet/dist/images/marker-icon-2x.png';
import icon from 'leaflet/dist/images/marker-icon.png';
import shadow from 'leaflet/dist/images/marker-shadow.png';

// eslint-disable-next-line @typescript-eslint/no-explicit-any -- Leafleti sisemine API
delete (L.Icon.Default.prototype as any)._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: iconRetina,
    iconUrl: icon,
    shadowUrl: shadow,
});
