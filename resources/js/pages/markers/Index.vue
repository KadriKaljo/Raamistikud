<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router} from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

type Marker = {
    id: number;
    name: string;
    latitude: number;
    longitude: number;
    description: string | null;
    added: string;
    edited: string | null;
}

const props = defineProps<{
    markers: Marker[];
}>();

const breadcrumbs: BreadcrumbItem[] = [ // see toob breadcrumbsi leheküljele
    {
        title: 'Markers',
        href: '/markers',
    },
];

const deleteMarker = (id: number) => {
    if (!confirm('Kas kustutada marker?')) return;

    router.delete(`/markers/${id}`, {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Marker kustutatud.');
        },
        onError: (err) => {
            console.error(err);
            alert('Kustutamine ebaõnnestus.');
        },
    });
};
</script>

<template>
    <Head title="Markers" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="mx-auto max-w-5xl p-6 space-y-4">
        <h1 class="text-2xl font-semibold">Markers</h1>
        <div class="overflow-x-auto rounded-xl border">
          <table class="w-full text-sm">
            <thead class="bg-muted/50">
              <tr>
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Latitude</th>
                <th class="px-3 py-2 text-left">Longitude</th>
                <th class="px-3 py-2 text-left">Description</th>
                <th class="px-3 py-2 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="marker in props.markers" :key="marker.id" class="border-t">
                <td class="px-3 py-2">{{ marker.name }}</td>
                <td class="px-3 py-2">{{ marker.latitude }}</td>
                <td class="px-3 py-2">{{ marker.longitude }}</td>
                <td class="px-3 py-2">{{ marker.description ?? '-' }}</td>
                <td class="px-3 py-2 text-right space-x-2">
                  <Link :href="`/markers/${marker.id}`" class="underline">View</Link>
                  <Link :href="`/markers/${marker.id}/edit`" class="underline">Edit</Link>
                  <button class="underline text-red-600" @click="deleteMarker(marker.id)">Delete</button>
                </td>
              </tr>
              <tr v-if="props.markers.length === 0">
                <td class="px-3 py-4 text-muted-foreground" colspan="5">Markereid pole veel lisatud.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </AppLayout>
  </template>