<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import '@/lib/leafletDefaultIcon';
import { buildMarkerPopupHtml } from '@/utils/markerPopupHtml';

const props = defineProps<{
    id: number;
    latitude: number;
    longitude: number;
    name: string;
    description: string | null;
}>();

const mapEl = ref<HTMLElement | null>(null);
let mapInstance: L.Map | null = null;

onMounted(() => {
    if (!mapEl.value) return;
    const lat = Number(props.latitude);
    const lng = Number(props.longitude);
    mapInstance = L.map(mapEl.value, { zoomControl: true, scrollWheelZoom: true }).setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapInstance);
    const marker = L.marker([lat, lng]).addTo(mapInstance).bindPopup(
        buildMarkerPopupHtml({
            id: props.id,
            name: props.name,
            latitude: props.latitude,
            longitude: props.longitude,
            description: props.description,
        }),
        { maxWidth: 320, className: 'marker-popup-rich' },
    );
    marker.openPopup();
});

onUnmounted(() => {
    mapInstance?.remove();
    mapInstance = null;
});
</script>

<template>
    <div ref="mapEl" class="h-64 min-h-64 w-full rounded-xl border border-border md:h-80" />
</template>
