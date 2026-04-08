<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import MapView from '@/components/MapView.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index as mapIndex } from '@/routes/map';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Markerite kaart', href: mapIndex().url },
];

defineProps<{
    markers: Array<{
        id: number;
        name: string;
        latitude: number;
        longitude: number;
        description: string | null;
        added: string;
        edited: string | null;
    }>;
}>();

function goDashboard() {
    router.get(dashboard().url, {}, { preserveScroll: true });
}
</script>

<template>
    <Head title="Markerite kaart" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-2xl bg-gradient-to-b from-muted/30 via-background to-background p-4 md:p-6 dark:from-muted/10"
        >
            <div class="flex flex-wrap items-center gap-2">
                <Button type="button" variant="outline" size="sm" class="rounded-full border-dashed" @click="goDashboard">
                    ← Tagasi valikusse
                </Button>
            </div>

            <div
                class="flex min-h-0 flex-col overflow-hidden rounded-2xl border border-emerald-200/50 bg-card shadow-md dark:border-emerald-900/40"
            >
                <div
                    class="flex flex-wrap items-center justify-between gap-2 border-b border-emerald-200/40 bg-gradient-to-r from-emerald-50/80 to-transparent px-4 py-3.5 dark:border-emerald-900/40 dark:from-emerald-950/30"
                >
                    <div>
                        <p class="text-sm font-semibold tracking-tight">Markerid</p>
                        <p class="text-xs text-muted-foreground">Kliki kaardil, et lisada marker</p>
                    </div>
                    <Link
                        href="/markers"
                        class="shrink-0 rounded-full bg-emerald-500/10 px-3 py-1.5 text-xs font-medium text-emerald-800 transition hover:bg-emerald-500/20 dark:text-emerald-300"
                    >
                        Kõik markerid →
                    </Link>
                </div>

                <div class="relative min-h-[min(50vh,420px)] flex-1 lg:min-h-[min(65vh,560px)]">
                    <MapView :markers="markers" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
