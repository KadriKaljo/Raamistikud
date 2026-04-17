<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as mapIndex } from '@/routes/map';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen, Clapperboard, CloudSun, MapPinned, Plane, ShoppingBag } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-2xl bg-gradient-to-b from-muted/30 via-background to-background p-4 md:p-6 dark:from-muted/10"
        >
            <div class="space-y-6">
                <div
                    class="rounded-2xl border border-border/50 bg-card/60 px-5 py-4 shadow-sm backdrop-blur-sm dark:bg-card/40"
                >
                    <h1 class="text-xl font-semibold tracking-tight md:text-2xl">Tere tulemast</h1>
                    <p class="mt-1 text-sm text-muted-foreground">Vali tegevus</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="flex flex-col gap-4">
                        <Link
                            href="/weather"
                            class="group relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-sky-200/60 bg-gradient-to-br from-sky-50/90 via-white to-sky-50/40 p-6 text-left shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-sky-300/80 hover:shadow-md dark:border-sky-900/50 dark:from-sky-950/35 dark:via-card dark:to-sky-950/20 dark:hover:border-sky-700/60"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <span
                                    class="inline-flex rounded-xl bg-sky-500/15 p-2.5 text-sky-600 ring-1 ring-sky-500/20 dark:bg-sky-400/10 dark:text-sky-300"
                                >
                                    <CloudSun class="size-6" aria-hidden="true" />
                                </span>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-widest text-sky-600/80 dark:text-sky-400/90"
                                    >Ilm</span
                                >
                            </div>
                            <div class="space-y-1">
                                <span class="block text-lg font-semibold tracking-tight">Ilmateade</span>
                                <span class="block text-sm leading-relaxed text-muted-foreground"
                                    >Hetkeilm ja linna otsing</span
                                >
                            </div>
                            <span
                                class="text-sm font-medium text-sky-700 transition group-hover:text-sky-800 dark:text-sky-400 dark:group-hover:text-sky-300"
                                >Ava ilmavaade →</span
                            >
                        </Link>

                        <Link
                            :href="mapIndex().url"
                            class="group relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-emerald-200/60 bg-gradient-to-br from-emerald-50/90 via-white to-teal-50/40 p-6 text-left shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-300/80 hover:shadow-md dark:border-emerald-900/50 dark:from-emerald-950/35 dark:via-card dark:to-teal-950/20 dark:hover:border-emerald-700/60"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <span
                                    class="inline-flex rounded-xl bg-emerald-500/15 p-2.5 text-emerald-600 ring-1 ring-emerald-500/20 dark:bg-emerald-400/10 dark:text-emerald-300"
                                >
                                    <MapPinned class="size-6" aria-hidden="true" />
                                </span>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-widest text-emerald-700/80 dark:text-emerald-400/90"
                                    >Kaart</span
                                >
                            </div>
                            <div class="space-y-1">
                                <span class="block text-lg font-semibold tracking-tight">Markerid</span>
                                <span class="block text-sm leading-relaxed text-muted-foreground"
                                    >Interaktiivne kaart ja salvestamine</span
                                >
                            </div>
                            <span
                                class="text-sm font-medium text-emerald-800 transition group-hover:text-emerald-900 dark:text-emerald-400 dark:group-hover:text-emerald-300"
                                >Ava kaart →</span
                            >
                        </Link>

                        <Link
                            href="/travel-destinations"
                            class="group relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-cyan-200/60 bg-gradient-to-br from-cyan-50/90 via-white to-blue-50/40 p-6 text-left shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-cyan-300/80 hover:shadow-md dark:border-cyan-900/50 dark:from-cyan-950/35 dark:via-card dark:to-blue-950/20 dark:hover:border-cyan-700/60"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <span
                                    class="inline-flex rounded-xl bg-cyan-500/15 p-2.5 text-cyan-700 ring-1 ring-cyan-500/20 dark:bg-cyan-400/10 dark:text-cyan-300"
                                >
                                    <Plane class="size-6" aria-hidden="true" />
                                </span>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-widest text-cyan-800/80 dark:text-cyan-400/90"
                                    >Reis</span
                                >
                            </div>
                            <div class="space-y-1">
                                <span class="block text-lg font-semibold tracking-tight">Reisisihtkohad</span>
                                <span class="block text-sm leading-relaxed text-muted-foreground"
                                    >Lisa, sirvi ja filtreeri sihtkohti</span
                                >
                            </div>
                            <span
                                class="text-sm font-medium text-cyan-900 transition group-hover:text-cyan-950 dark:text-cyan-400 dark:group-hover:text-cyan-300"
                                >Ava reisivaade →</span
                            >
                        </Link>
                    </div>

                    <div class="flex flex-col gap-4">
                        <Link
                            href="/products"
                            class="group relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-amber-200/60 bg-gradient-to-br from-amber-50/90 via-white to-orange-50/40 p-6 text-left shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-amber-300/80 hover:shadow-md dark:border-amber-900/50 dark:from-amber-950/35 dark:via-card dark:to-orange-950/20 dark:hover:border-amber-700/60"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <span
                                    class="inline-flex rounded-xl bg-amber-500/15 p-2.5 text-amber-700 ring-1 ring-amber-500/20 dark:bg-amber-400/10 dark:text-amber-300"
                                >
                                    <ShoppingBag class="size-6" aria-hidden="true" />
                                </span>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-widest text-amber-800/80 dark:text-amber-400/90"
                                    >Pood</span
                                >
                            </div>
                            <div class="space-y-1">
                                <span class="block text-lg font-semibold tracking-tight">E-pood</span>
                                <span class="block text-sm leading-relaxed text-muted-foreground"
                                    >Tooted ja ostukorv</span
                                >
                            </div>
                            <span
                                class="text-sm font-medium text-amber-900 transition group-hover:text-amber-950 dark:text-amber-400 dark:group-hover:text-amber-300"
                                >Mine toodete juurde →</span
                            >
                        </Link>

                        <Link
                            href="/posts"
                            class="group relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-violet-200/60 bg-gradient-to-br from-violet-50/90 via-white to-fuchsia-50/40 p-6 text-left shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-violet-300/80 hover:shadow-md dark:border-violet-900/50 dark:from-violet-950/35 dark:via-card dark:to-fuchsia-950/20 dark:hover:border-violet-700/60"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <span
                                    class="inline-flex rounded-xl bg-violet-500/15 p-2.5 text-violet-700 ring-1 ring-violet-500/20 dark:bg-violet-400/10 dark:text-violet-300"
                                >
                                    <BookOpen class="size-6" aria-hidden="true" />
                                </span>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-widest text-violet-800/80 dark:text-violet-400/90"
                                    >Blogi</span
                                >
                            </div>
                            <div class="space-y-1">
                                <span class="block text-lg font-semibold tracking-tight">Blogi</span>
                                <span class="block text-sm leading-relaxed text-muted-foreground"
                                    >Postitused ja kommentaarid</span
                                >
                            </div>
                            <span
                                class="text-sm font-medium text-violet-900 transition group-hover:text-violet-950 dark:text-violet-400 dark:group-hover:text-violet-300"
                                >Mine blogisse →</span
                            >
                        </Link>

                        <Link
                            href="/travel-destinations/movies"
                            class="group relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-fuchsia-200/60 bg-gradient-to-br from-fuchsia-50/90 via-white to-pink-50/40 p-6 text-left shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-fuchsia-300/80 hover:shadow-md dark:border-fuchsia-900/50 dark:from-fuchsia-950/35 dark:via-card dark:to-pink-950/20 dark:hover:border-fuchsia-700/60"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <span
                                    class="inline-flex rounded-xl bg-fuchsia-500/15 p-2.5 text-fuchsia-700 ring-1 ring-fuchsia-500/20 dark:bg-fuchsia-400/10 dark:text-fuchsia-300"
                                >
                                    <Clapperboard class="size-6" aria-hidden="true" />
                                </span>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-widest text-fuchsia-800/80 dark:text-fuchsia-400/90"
                                    >API</span
                                >
                            </div>
                            <div class="space-y-1">
                                <span class="block text-lg font-semibold tracking-tight">Filmid (API)</span>
                                <span class="block text-sm leading-relaxed text-muted-foreground"
                                    >Välise JSON API andmete kuvamine</span
                                >
                            </div>
                            <span
                                class="text-sm font-medium text-fuchsia-900 transition group-hover:text-fuchsia-950 dark:text-fuchsia-400 dark:group-hover:text-fuchsia-300"
                                >Ava filmivaade →</span
                            >
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
