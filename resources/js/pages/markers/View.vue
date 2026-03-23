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
    marker: Marker;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Markers', href: '/markers',
    },
    {
        title: props.marker.name, href: `/markers/${props.marker.id}`,
    },
];

</script>


<template>
    <Head :title="`Marker #${props.marker.id}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="mx-auto max-w-3xl p-6 space-y-4">
        <h1 class="text-2xl font-semibold">{{ props.marker.name }}</h1>
        <div class="rounded-xl border p-4 space-y-2">
          <p><span class="font-medium">Latitude:</span> {{ props.marker.latitude }}</p>
          <p><span class="font-medium">Longitude:</span> {{ props.marker.longitude }}</p>
          <p><span class="font-medium">Description:</span> {{ props.marker.description ?? '-' }}</p>
          <p><span class="font-medium">Added:</span> {{ props.marker.added }}</p>
          <p><span class="font-medium">Edited:</span> {{ props.marker.edited ?? '-' }}</p>
        </div>
        <div class="flex gap-3">
          <Link :href="`/markers/${props.marker.id}/edit`" class="underline">Edit</Link>
          <Link href="/markers" class="underline">Back</Link>
        </div>
      </div>
    </AppLayout>
  </template>