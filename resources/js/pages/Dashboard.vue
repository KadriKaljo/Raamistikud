<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { WeatherData, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import MapView from '@/components/MapView.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

defineProps<{
    weather: WeatherData;
    requestedCity: string;
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
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <form
                :action="dashboard().url"
                method="get"
                class="flex flex-col gap-3 sm:flex-row sm:items-end sm:gap-4"
            >
                <div class="grid w-full max-w-md gap-2">
                    <Label for="dashboard-city">Sisesta linn</Label>
                    <Input
                        id="dashboard-city"
                        name="city"
                        type="text"
                        :default-value="requestedCity"
                        placeholder="Nt Tartu või London, UK"
                        autocomplete="off"
                    />
                </div>
                <Button type="submit">Otsi</Button>
            </form>

            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="rounded-2xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border dark:bg-card"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm text-muted-foreground">Hetkeilm</p>

                            <h2 class="mt-2 text-4xl font-bold leading-none tracking-tight">
                                {{ Math.round(weather.main.temp) }} °C
                            </h2>

                            <p class="mt-3 text-lg font-medium capitalize">
                                {{ weather.weather[0].description }}
                            </p>

                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ weather.name }}, {{ weather.sys.country }}
                            </p>
                        </div>

                        <div class="shrink-0">
                            <img
                                class="h-48 w-48"
                                :src="`https://openweathermap.org/img/wn/${weather.weather[0].icon}@2x.png`"
                                alt="Ilmaikoon"
                            />
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-muted/40 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-muted-foreground">
                                💨 Tuule kiirus
                            </p>
                            <p class="mt-1 text-sm font-medium">
                                {{ weather.wind.speed }} m/s
                            </p>
                        </div>

                        <div class="rounded-xl bg-muted/40 px-4 py-3">
                            <p class="text-xs uppercase tracking-wide text-muted-foreground">
                                💧 Õhuniiskus
                            </p>
                            <p class="mt-1 text-sm font-medium">
                                {{ weather.main.humidity }}%
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <PlaceholderPattern />
                </div>

                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <PlaceholderPattern />
                </div>
            </div>

            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
            >
                <MapView :markers="markers" />
            </div>
        </div>
    </AppLayout>
</template>