<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from "vue"
import L, {type LeafletMouseEvent} from "leaflet"
import "leaflet/dist/leaflet.css"
import "@/lib/leafletDefaultIcon"
import { Dialog,DialogContent,DialogHeader,DialogTitle,DialogDescription } from "./ui/dialog";
import { Form } from "@inertiajs/vue3";
import { Label } from "./ui/label";
import Input from "./ui/input/Input.vue";
import Textarea from "./ui/textarea/Textarea.vue";
import Button from "./ui/button/Button.vue";
import { buildMarkerPopupHtml } from "@/utils/markerPopupHtml";

const props = defineProps<{
    markers: Array<{
        id: number;
        name: string;
        latitude: number;
        longitude: number;
        description: string | null;
    }>;
}>();

const mapEl = ref<HTMLElement | null>(null);
const selectedLocation = ref<{ lat: number; lng: number } | undefined>();
let mapInstance: L.Map | null = null;
let markersLayer: L.LayerGroup | null = null;

const mapClick= (e:LeafletMouseEvent)=> {
    selectedLocation.value = {
        lat: e.latlng.lat,
        lng: e.latlng.lng
    }
};

const closeDialog = () => {
    selectedLocation.value = undefined;
};

const renderMarkers = () => {
  if (!markersLayer) return;

  markersLayer.clearLayers();
  props.markers.forEach((marker) => {
    L.marker([marker.latitude, marker.longitude])
      .addTo(markersLayer!)
      .bindPopup(buildMarkerPopupHtml(marker), { maxWidth: 320, className: "marker-popup-rich" });
  });
};

onMounted(() => {
  if (!mapEl.value) return // lisame sest mapEl.value ei ole alati olemas
  mapInstance = L.map(mapEl.value, {
    zoomControl: true
  }).setView([59.4, 24.7], 8)

  L.tileLayer(
    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
  ).addTo(mapInstance)

  markersLayer = L.layerGroup().addTo(mapInstance);
  renderMarkers();

  mapInstance.on('click', mapClick);
});

watch(
  () => props.markers,
  () => {
    renderMarkers();
  },
  { deep: true },
);

onUnmounted(() => {
  if (mapInstance) {
    mapInstance.off('click', mapClick);
    mapInstance.remove();
  }
  mapInstance = null;
  markersLayer = null;
});

</script>

<template>
    <div ref="mapEl" class="z-10 h-full"></div>
    <Dialog :open="!!selectedLocation" @update:open="closeDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Salvesta uus marker</DialogTitle>
          <DialogDescription>
            Lisa nimi ja kirjeldus
          </DialogDescription>

          <Form method="post" action="/markers" @success="closeDialog">
            
             <div class="grid grid-cols-2 gap-4">
              <div class="col-span-2">
                  <Label class="mb-1.5" for="name">Nimi</Label>
                  <Input id="name" name="name"/>
              </div>
              <div>
                  <Label class="mb-1.5" for="lat">Laiuskraad</Label>
                  <Input id="lat" :model-value="selectedLocation?.lat ?? ''" readonly />
              </div>
              <div>
                  <Label class="mb-1.5" for="lng">Pikkuskraad</Label>
                  <Input id="lng" :model-value="selectedLocation?.lng ?? ''" readonly />
              </div>

              <input type="hidden" name="latitude" :value="selectedLocation?.lat ?? ''" /> 
              <input type="hidden" name="longitude" :value="selectedLocation?.lng ?? ''" />

              <Textarea name="description" class="col-span-2"></Textarea>
              <Button class="col-span-2" type="submit"> Salvesta </Button>
             </div>
          </Form>
        </DialogHeader>
      </DialogContent>
    </Dialog>
    
  </template>