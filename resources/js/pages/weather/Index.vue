<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { WeatherData, type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { ref, watch, computed } from 'vue';
import { Droplets, Wind } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Ilmateade',
        href: '/weather',
    },
];

const props = defineProps<{
    weather: WeatherData;
    requestedCity: string;
}>();

const cityQuery = ref(props.requestedCity);

const page = usePage();
const cityError = computed(() => {
    const errors = page.props.errors as Record<string, string> | undefined;
    return errors?.city;
});

watch(
    () => props.requestedCity,
    (v) => {
        cityQuery.value = v;
    },
);

function runWeatherSearch() {
    const city = cityQuery.value.trim();
    router.get('/weather', city === '' ? {} : { city }, { preserveScroll: true });
}

function goHome() {
    router.get(dashboard().url, {}, { preserveScroll: true });
}
</script>

<template>
    <Head title="Ilmateade" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto flex w-full max-w-xl flex-col gap-6 overflow-x-auto rounded-2xl bg-gradient-to-b from-muted/30 via-background to-background p-4 md:p-6 dark:from-muted/10"
        >
            <div class="flex flex-col gap-5">
                <div class="flex items-center gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        class="rounded-full border-dashed"
                        @click="goHome"
                    >
                        ← Tagasi valikusse
                    </Button>
                </div>

                <form
                    class="flex flex-col gap-3 rounded-2xl border border-border/60 bg-card/80 p-4 shadow-sm backdrop-blur-sm sm:flex-row sm:items-end sm:gap-4 dark:bg-card/50"
                    @submit.prevent="runWeatherSearch"
                >
                    <div class="grid w-full min-w-0 flex-1 gap-2 sm:max-w-md">
                        <Label for="weather-city" class="text-foreground/90">Sisesta linn</Label>
                        <Input
                            id="weather-city"
                            v-model="cityQuery"
                            type="text"
                            name="city"
                            maxlength="100"
                            placeholder="Nt Tartu või London, UK"
                            autocomplete="off"
                        />
                        <InputError :message="cityError" />
                    </div>
                    <Button type="submit" class="shrink-0 rounded-xl">Otsi</Button>
                </form>

                <div
                    class="overflow-hidden rounded-2xl border border-sky-200/50 bg-gradient-to-br from-sky-50 via-white to-blue-50/80 p-6 shadow-md dark:border-sky-900/40 dark:from-sky-950/40 dark:via-card dark:to-blue-950/25"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-widest text-sky-600/80 dark:text-sky-400/90">
                                Hetkeilm
                            </p>

                            <h2 class="text-4xl font-bold tabular-nums tracking-tight text-foreground">
                                {{ Math.round(weather.main.temp) }}
                                <span class="text-2xl font-semibold text-muted-foreground">°C</span>
                            </h2>

                            <p class="pt-1 text-lg font-medium capitalize leading-snug text-foreground/90">
                                {{ weather.weather[0].description }}
                            </p>

                            <p class="text-sm text-muted-foreground">
                                {{ weather.name
                                }}<template v-if="weather.sys?.country">, {{ weather.sys.country }}</template>
                            </p>
                        </div>

                        <div
                            class="shrink-0 rounded-2xl bg-white/90 p-3 shadow-inner ring-1 ring-sky-100 dark:bg-white/5 dark:ring-sky-900/50"
                        >
                            <img
                                class="h-20 w-20 object-contain sm:h-24 sm:w-24"
                                :src="`https://openweathermap.org/img/wn/${weather.weather[0].icon}@2x.png`"
                                alt=""
                            />
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div
                            class="flex gap-3 rounded-xl border border-border/50 bg-white/70 px-4 py-3 dark:bg-muted/30"
                        >
                            <span
                                class="mt-0.5 inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400"
                            >
                                <Wind class="size-4" aria-hidden="true" />
                            </span>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-muted-foreground">
                                    Tuule kiirus
                                </p>
                                <p class="mt-0.5 text-sm font-semibold tabular-nums">
                                    {{ weather.wind.speed }} m/s
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex gap-3 rounded-xl border border-border/50 bg-white/70 px-4 py-3 dark:bg-muted/30"
                        >
                            <span
                                class="mt-0.5 inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400"
                            >
                                <Droplets class="size-4" aria-hidden="true" />
                            </span>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-muted-foreground">
                                    Õhuniiskus
                                </p>
                                <p class="mt-0.5 text-sm font-semibold tabular-nums">
                                    {{ weather.main.humidity }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-center text-xs leading-relaxed text-muted-foreground">
                Ilmaandmed:
                <a
                    class="font-medium text-sky-700 underline-offset-2 hover:underline dark:text-sky-400"
                    href="https://openweathermap.org/"
                    target="_blank"
                    rel="noopener noreferrer"
                >OpenWeatherMap</a>.
            </p>
        </div>
    </AppLayout>
</template>
