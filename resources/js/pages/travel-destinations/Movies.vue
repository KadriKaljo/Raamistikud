<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Clapperboard } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

type GenericMovie = Record<string, unknown>;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Filmid (välis-API)', href: '/travel-destinations/movies' },
];

const loading = ref(true);
const error = ref<string | null>(null);
const movies = ref<GenericMovie[]>([]);

onMounted(async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await fetch('https://ralfiharjutus.ta24siim.itmajakas.ee/api/movies?limit=20');
        if (!response.ok) {
            throw new Error('HTTP error');
        }

        const data = await response.json();
        const list = Array.isArray(data) ? data : data.data;
        movies.value = Array.isArray(list) ? list : [];
    } catch {
        error.value = 'Filmiandmete laadimine ebaõnnestus. Proovi hiljem uuesti.';
    } finally {
        loading.value = false;
    }
});

function movieTitle(movie: GenericMovie): string {
    const fallback = 'Pealkiri puudub';

    if (typeof movie.title === 'string' && movie.title.trim() !== '') return movie.title;
    if (typeof movie.name === 'string' && movie.name.trim() !== '') return movie.name;
    if (typeof movie.movie_title === 'string' && movie.movie_title.trim() !== '') return movie.movie_title;

    return fallback;
}

function movieField(movie: GenericMovie, keys: string[]): string | null {
    for (const key of keys) {
        const value = movie[key];
        if (typeof value === 'string' && value.trim() !== '') return value;
        if (typeof value === 'number') return String(value);
    }
    return null;
}

function movieYear(movie: GenericMovie): string {
    return movieField(movie, ['year', 'release_year', 'releaseYear', 'release_date', 'date']) ?? 'Puudub';
}

function movieGenre(movie: GenericMovie): string {
    return movieField(movie, ['genre', 'genres', 'category']) ?? 'Määramata';
}

function movieRating(movie: GenericMovie): string {
    return movieField(movie, ['rating', 'imdb_rating', 'score']) ?? 'Puudub';
}

function movieDuration(movie: GenericMovie): string {
    return movieField(movie, ['duration', 'runtime', 'length']) ?? 'Puudub';
}

function movieDescription(movie: GenericMovie): string {
    return (
        movieField(movie, ['description', 'overview', 'summary', 'plot']) ??
        'Kirjeldus puudub.'
    );
}
</script>

<template>
    <Head title="Filmid (välis-API)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 p-4 md:p-6">
            <section class="rounded-2xl border border-violet-200/60 bg-gradient-to-br from-violet-50/90 via-white to-fuchsia-50/40 p-6 shadow-sm dark:border-violet-900/50 dark:from-violet-950/35 dark:via-card dark:to-fuchsia-950/20">
                <div class="space-y-2">
                    <span class="inline-flex rounded-xl bg-violet-500/15 p-2.5 text-violet-700 ring-1 ring-violet-500/20 dark:bg-violet-400/10 dark:text-violet-300">
                        <Clapperboard class="size-5" />
                    </span>
                    <h1 class="text-2xl font-semibold tracking-tight">Kaasõpilase movies API</h1>
                </div>
                <p class="mt-1 text-sm text-muted-foreground">
                    <code>https://ralfiharjutus.ta24siim.itmajakas.ee/api/movies?limit=20</code>
                </p>
            </section>

            <section v-if="loading" class="rounded-2xl border border-sky-200/60 bg-gradient-to-br from-sky-50/90 via-white to-blue-50/40 p-10 text-center text-sm text-muted-foreground dark:border-sky-900/50 dark:from-sky-950/35 dark:via-card dark:to-blue-950/20">
                Laen filmiandmeid...
            </section>

            <section v-else-if="error" class="rounded-2xl border border-red-300/60 bg-red-50/50 p-6 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/20 dark:text-red-300">
                {{ error }}
            </section>

            <section v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <article
                    v-for="(movie, idx) in movies"
                    :key="idx"
                    class="rounded-xl border border-border/60 bg-gradient-to-br from-card to-muted/20 p-4 shadow-sm"
                >
                    <h2 class="text-base font-semibold">{{ movieTitle(movie) }}</h2>

                    <dl class="mt-3 space-y-2 text-sm">
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-muted-foreground">Aasta</dt>
                            <dd class="text-right font-medium">{{ movieYear(movie) }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-muted-foreground">Žanr</dt>
                            <dd class="text-right font-medium">{{ movieGenre(movie) }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-muted-foreground">Hinnang</dt>
                            <dd class="text-right font-medium">{{ movieRating(movie) }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-muted-foreground">Kestus</dt>
                            <dd class="text-right font-medium">{{ movieDuration(movie) }}</dd>
                        </div>
                    </dl>

                    <p class="mt-3 line-clamp-4 text-sm text-muted-foreground">
                        {{ movieDescription(movie) }}
                    </p>
                </article>
            </section>
        </div>
    </AppLayout>
</template>
