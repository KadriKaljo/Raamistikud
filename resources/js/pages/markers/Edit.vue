<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

type Marker = {
  id: number;
  name: string;
  latitude: number;
  longitude: number;
  description: string | null;
};

const props = defineProps<{
  marker: Marker;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Markers', href: '/markers' },
  { title: `Edit #${props.marker.id}`, href: `/markers/${props.marker.id}/edit` },
];

const form = useForm({
  name: props.marker.name,
  latitude: props.marker.latitude,
  longitude: props.marker.longitude,
  description: props.marker.description ?? '',
});

const submit = () => {
  form.put(`/markers/${props.marker.id}`, {
    preserveScroll: true,
  });
};
</script>

<template>
  <Head :title="`Edit marker #${props.marker.id}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="mx-auto max-w-2xl p-6 space-y-4">
      <h1 class="text-2xl font-semibold">Edit Marker</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="mb-1 block text-sm font-medium">Name</label>
          <input v-model="form.name" class="w-full rounded-md border px-3 py-2" />
          <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium">Latitude</label>
          <input v-model="form.latitude" type="number" step="any" class="w-full rounded-md border px-3 py-2" />
          <p v-if="form.errors.latitude" class="mt-1 text-sm text-red-600">{{ form.errors.latitude }}</p>
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium">Longitude</label>
          <input v-model="form.longitude" type="number" step="any" class="w-full rounded-md border px-3 py-2" />
          <p v-if="form.errors.longitude" class="mt-1 text-sm text-red-600">{{ form.errors.longitude }}</p>
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium">Description</label>
          <textarea v-model="form.description" class="w-full rounded-md border px-3 py-2" />
          <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
        </div>

        <div class="flex gap-3">
          <button type="submit" class="rounded-md border px-3 py-2">Save</button>
          <Link href="/markers" class="rounded-md border px-3 py-2">Cancel</Link>
        </div>
      </form>
    </div>
  </AppLayout>
</template>