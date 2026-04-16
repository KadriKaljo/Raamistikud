<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Plane } from 'lucide-vue-next';
import { ref } from 'vue';

type TravelDestination = {
    id: number;
    title: string;
    image: string | null;
    description: string;
    country: string;
    best_season: string;
    created_at: string;
};

type PaginatedDestinations = {
    data: TravelDestination[];
    links: Array<{ url: string | null; label: string; active: boolean }>;
};

const props = defineProps<{
    destinations: PaginatedDestinations;
    filters: {
        q: string;
        country: string;
        best_season: string;
        sort: 'title' | 'country' | 'best_season' | 'created_at';
        direction: 'asc' | 'desc';
    };
    countries: string[];
    seasons: string[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Reisisihtkohad', href: '/travel-destinations' },
];

const createForm = useForm({
    title: '',
    image: '',
    description: '',
    country: '',
    best_season: '',
});

const filterState = ref({
    q: props.filters.q ?? '',
    country: props.filters.country ?? '',
    best_season: props.filters.best_season ?? '',
    sort: props.filters.sort ?? 'created_at',
    direction: props.filters.direction ?? 'desc',
});

function submitCreateForm() {
    createForm.post('/travel-destinations', {
        preserveScroll: true,
        onSuccess: () => createForm.reset(),
    });
}

function applyFilters() {
    router.get('/travel-destinations', filterState.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function resetFilters() {
    filterState.value = {
        q: '',
        country: '',
        best_season: '',
        sort: 'created_at',
        direction: 'desc',
    };
    applyFilters();
}

function destinationImage(destination: TravelDestination): string {
    if (destination.image && destination.image.trim() !== '') {
        return destination.image;
    }

    const seed = destination.title.toLowerCase().replace(/\s+/g, '-');
    return `https://picsum.photos/seed/travel-${seed}/800/500`;
}
</script>

<template>
    <Head title="Reisisihtkohad" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 p-4 md:p-6">
            <section class="rounded-2xl border border-cyan-200/60 bg-gradient-to-br from-cyan-50/90 via-white to-blue-50/40 p-6 shadow-sm dark:border-cyan-900/50 dark:from-cyan-950/35 dark:via-card dark:to-blue-950/20">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="space-y-2">
                        <span class="inline-flex rounded-xl bg-cyan-500/15 p-2.5 text-cyan-700 ring-1 ring-cyan-500/20 dark:bg-cyan-400/10 dark:text-cyan-300">
                            <Plane class="size-5" />
                        </span>
                        <h1 class="text-2xl font-semibold tracking-tight">Lemmik reisisihtkohad</h1>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Lisa reisisihtkohti, filtreeri neid ja kasuta sama andmestikku JSON API kaudu.
                        </p>
                    </div>
                </div>
            </section>

            <section class="grid gap-6">
                <form class="space-y-4 rounded-2xl border border-emerald-200/60 bg-gradient-to-br from-emerald-50/90 via-white to-teal-50/40 p-5 shadow-sm dark:border-emerald-900/50 dark:from-emerald-950/35 dark:via-card dark:to-teal-950/20" @submit.prevent="submitCreateForm">
                    <h2 class="text-lg font-semibold">Lisa uus reisisihtkoht</h2>

                    <div class="grid gap-2">
                        <Label for="title">Pealkiri</Label>
                        <Input id="title" v-model="createForm.title" />
                        <InputError :message="createForm.errors.title" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="image">Pildi URL</Label>
                        <Input id="image" v-model="createForm.image" placeholder="https://..." />
                        <InputError :message="createForm.errors.image" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Kirjeldus</Label>
                        <textarea id="description" v-model="createForm.description" class="min-h-28 rounded-lg border border-border bg-background px-3 py-2 text-sm" />
                        <InputError :message="createForm.errors.description" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="country">Riik</Label>
                            <Input id="country" v-model="createForm.country" />
                            <InputError :message="createForm.errors.country" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="best-season">Parim hooaeg</Label>
                            <Input id="best-season" v-model="createForm.best_season" placeholder="nt Kevad" />
                            <InputError :message="createForm.errors.best_season" />
                        </div>
                    </div>

                    <Button type="submit" :disabled="createForm.processing">Salvesta sihtkoht</Button>
                </form>
            </section>

            <section class="space-y-4 rounded-2xl border border-sky-200/60 bg-gradient-to-br from-sky-50/90 via-white to-cyan-50/40 p-5 shadow-sm dark:border-sky-900/50 dark:from-sky-950/35 dark:via-card dark:to-cyan-950/20">
                <h2 class="text-lg font-semibold">Sirvi reisisihtkohti</h2>

                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-6">
                    <Input v-model="filterState.q" class="lg:col-span-2" placeholder="Otsi pealkirja, kirjelduse või riigi järgi..." />

                    <select v-model="filterState.country" class="rounded-lg border border-border bg-background px-3 py-2 text-sm">
                        <option value="">Kõik riigid</option>
                        <option v-for="country in countries" :key="country" :value="country">{{ country }}</option>
                    </select>

                    <select v-model="filterState.best_season" class="rounded-lg border border-border bg-background px-3 py-2 text-sm">
                        <option value="">Kõik hooajad</option>
                        <option v-for="season in seasons" :key="season" :value="season">{{ season }}</option>
                    </select>

                    <select v-model="filterState.sort" class="rounded-lg border border-border bg-background px-3 py-2 text-sm">
                        <option value="created_at">Sorteeri: lisamise aeg</option>
                        <option value="title">Sorteeri: pealkiri</option>
                        <option value="country">Sorteeri: riik</option>
                        <option value="best_season">Sorteeri: hooaeg</option>
                    </select>

                    <select v-model="filterState.direction" class="rounded-lg border border-border bg-background px-3 py-2 text-sm">
                        <option value="desc">Järjestus: kahanev</option>
                        <option value="asc">Järjestus: kasvav</option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-2">
                    <Button type="button" @click="applyFilters">Rakenda filtrid</Button>
                    <Button type="button" variant="outline" @click="resetFilters">Lähtesta</Button>
                </div>

                <div v-if="destinations.data.length === 0" class="rounded-xl border border-border/60 p-6 text-center text-sm text-muted-foreground">
                    Ühtegi sihtkohta ei leitud.
                </div>

                <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <article v-for="destination in destinations.data" :key="destination.id" class="overflow-hidden rounded-xl border border-border/60 bg-background">
                        <img
                            :src="destinationImage(destination)"
                            :alt="destination.title"
                            class="h-40 w-full object-cover"
                        />
                        <div class="space-y-2 p-4">
                            <h3 class="text-base font-semibold">{{ destination.title }}</h3>
                            <p class="line-clamp-3 text-sm text-muted-foreground">{{ destination.description }}</p>
                            <div class="flex flex-wrap gap-2 pt-1 text-xs">
                                <span class="rounded-full bg-muted px-2.5 py-1">{{ destination.country }}</span>
                                <span class="rounded-full bg-muted px-2.5 py-1">{{ destination.best_season }}</span>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="flex flex-wrap gap-2">
                    <Link
                        v-for="link in destinations.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'rounded-lg border px-3 py-1.5 text-sm',
                            link.active ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-background',
                            !link.url && 'pointer-events-none opacity-50',
                        ]"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
