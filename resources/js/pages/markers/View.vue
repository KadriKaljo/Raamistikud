<script setup lang="ts">
import MarkerReadonlyMap from '@/components/MarkerReadonlyMap.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { index as mapIndex } from '@/routes/map';
import type { BreadcrumbItem } from '@/types';
import { computed } from 'vue';

type Marker = {
    id: number;
    name: string;
    latitude: number;
    longitude: number;
    description: string | null;
    added: string;
    edited: string | null;
};

const props = defineProps<{
    marker: Marker;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Markerid',
        href: '/markers',
    },
    {
        title: props.marker.name,
        href: `/markers/${props.marker.id}`,
    },
];

function formatEt(iso: string | null): string {
    if (!iso) return '—';
    const d = new Date(iso);
    if (Number.isNaN(d.getTime())) return iso;
    return new Intl.DateTimeFormat('et-EE', {
        dateStyle: 'full',
        timeStyle: 'short',
    }).format(d);
}

const coordLine = computed(() => {
    const lat = Number(props.marker.latitude);
    const lng = Number(props.marker.longitude);
    return `${lat.toFixed(7)}, ${lng.toFixed(7)}`;
});

const openStreetMapUrl = computed(() => {
    const lat = Number(props.marker.latitude);
    const lng = Number(props.marker.longitude);
    return `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=15/${lat}/${lng}`;
});

const mapHref = mapIndex().url;

function deleteMarker() {
    if (!confirm('Kas kustutada marker?')) return;
    router.delete(`/markers/${props.marker.id}`, {
        onError: () => alert('Kustutamine ebaõnnestus.'),
    });
}
</script>

<template>
    <Head :title="props.marker.name" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-5xl space-y-6 p-6">
            <div>
                <Link
                    :href="mapHref"
                    class="inline-flex items-center gap-1.5 rounded-lg text-sm font-medium text-emerald-800 transition hover:text-emerald-950 hover:underline dark:text-emerald-400 dark:hover:text-emerald-300"
                >
                    ← Tagasi kaardivaatesse
                </Link>
            </div>

            <div class="space-y-1">
                <p class="text-sm text-muted-foreground">Marker #{{ marker.id }}</p>
                <h1 class="text-2xl font-semibold tracking-tight">{{ marker.name }}</h1>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1fr_minmax(0,320px)]">
                <div class="space-y-1 rounded-xl border bg-card p-5 shadow-sm">
                    <dl class="divide-y divide-border">
                        <div class="grid gap-1 py-3 first:pt-0 sm:grid-cols-[minmax(8rem,30%)_1fr] sm:gap-4">
                            <dt class="text-sm font-medium text-muted-foreground">Nimi</dt>
                            <dd class="text-sm font-medium text-foreground">{{ marker.name }}</dd>
                        </div>
                        <div class="grid gap-1 py-3 sm:grid-cols-[minmax(8rem,30%)_1fr] sm:gap-4">
                            <dt class="text-sm font-medium text-muted-foreground">Laiuskraad</dt>
                            <dd class="font-mono text-sm">{{ Number(marker.latitude).toFixed(7) }}</dd>
                        </div>
                        <div class="grid gap-1 py-3 sm:grid-cols-[minmax(8rem,30%)_1fr] sm:gap-4">
                            <dt class="text-sm font-medium text-muted-foreground">Pikkuskraad</dt>
                            <dd class="font-mono text-sm">{{ Number(marker.longitude).toFixed(7) }}</dd>
                        </div>
                        <div class="grid gap-1 py-3 sm:grid-cols-[minmax(8rem,30%)_1fr] sm:gap-4">
                            <dt class="text-sm font-medium text-muted-foreground">Koordinaadid (kopeeri)</dt>
                            <dd class="break-all font-mono text-sm">{{ coordLine }}</dd>
                        </div>
                        <div class="grid gap-1 py-3 sm:grid-cols-[minmax(8rem,30%)_1fr] sm:gap-4">
                            <dt class="text-sm font-medium text-muted-foreground">Kirjeldus</dt>
                            <dd class="text-sm">
                                <span v-if="marker.description" class="whitespace-pre-wrap">{{ marker.description }}</span>
                                <span v-else class="text-muted-foreground">—</span>
                            </dd>
                        </div>
                        <div class="grid gap-1 py-3 sm:grid-cols-[minmax(8rem,30%)_1fr] sm:gap-4">
                            <dt class="text-sm font-medium text-muted-foreground">Lisatud</dt>
                            <dd class="text-sm">{{ formatEt(marker.added) }}</dd>
                        </div>
                        <div class="grid gap-1 py-3 sm:grid-cols-[minmax(8rem,30%)_1fr] sm:gap-4">
                            <dt class="text-sm font-medium text-muted-foreground">Viimati muudetud</dt>
                            <dd class="text-sm">{{ formatEt(marker.edited) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Asukoht</p>
                    <MarkerReadonlyMap
                        :id="marker.id"
                        :latitude="marker.latitude"
                        :longitude="marker.longitude"
                        :name="marker.name"
                        :description="marker.description"
                    />
                    <a
                        :href="openStreetMapUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex text-sm font-medium text-emerald-700 underline-offset-2 hover:underline dark:text-emerald-400"
                    >
                        Ava OpenStreetMapis uues vahekaardis →
                    </a>
                </div>
            </div>

            <div class="flex w-full flex-wrap items-center gap-3 border-t border-border pt-6">
                <Link
                    :href="`/markers/${marker.id}/edit`"
                    class="inline-flex items-center justify-center rounded-2xl border border-border bg-background px-4 py-2 text-sm font-medium transition hover:bg-muted"
                >
                    Muuda
                </Link>
                <Button
                    type="button"
                    variant="outline"
                    class="rounded-2xl border-red-200 bg-white text-red-600 shadow-xs hover:bg-red-50 hover:text-red-700 dark:border-red-800/60 dark:bg-background dark:text-red-400 dark:hover:bg-red-950/40 dark:hover:text-red-300"
                    @click="deleteMarker"
                >
                    Kustuta
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
