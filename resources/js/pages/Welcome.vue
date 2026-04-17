<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Avaleht" />

    <div class="min-h-screen bg-background text-foreground">
        <div class="mx-auto flex w-full max-w-5xl flex-col gap-6 p-6 md:p-8">
            <header class="flex items-center justify-end gap-3">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-flex items-center rounded-xl border border-border bg-card px-4 py-2 text-sm font-medium transition hover:bg-accent"
                >
                    Ava juhtpaneel
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-flex items-center rounded-xl border border-border bg-card px-4 py-2 text-sm font-medium transition hover:bg-accent"
                    >
                        Logi sisse
                    </Link>
                    <Link
                        :href="register()"
                        class="inline-flex items-center rounded-xl bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition hover:opacity-90"
                    >
                        Registreeru
                    </Link>
                </template>
            </header>

            <main class="rounded-2xl border border-border/60 bg-card/60 p-6 shadow-sm md:p-8">
                <div class="max-w-2xl">
                    <h1 class="text-2xl font-semibold tracking-tight md:text-3xl">Raamistikud projekt</h1>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground md:text-base">
                        Laravel + Vue harjutusprojekt mitme funktsionaalse vaatega: ilm, kaart, reisisihtkohad, e-pood,
                        blogi ja API-andmete kuvamine.
                    </p>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <Link href="/weather" class="rounded-xl border border-border bg-background px-4 py-3 text-sm font-medium transition hover:bg-accent"
                        >Ilm</Link
                    >
                    <Link href="/map" class="rounded-xl border border-border bg-background px-4 py-3 text-sm font-medium transition hover:bg-accent"
                        >Kaart</Link
                    >
                    <Link
                        href="/travel-destinations"
                        class="rounded-xl border border-border bg-background px-4 py-3 text-sm font-medium transition hover:bg-accent"
                        >Reisisihtkohad</Link
                    >
                    <Link href="/products" class="rounded-xl border border-border bg-background px-4 py-3 text-sm font-medium transition hover:bg-accent"
                        >E-pood</Link
                    >
                    <Link href="/posts" class="rounded-xl border border-border bg-background px-4 py-3 text-sm font-medium transition hover:bg-accent"
                        >Blogi</Link
                    >
                    <Link
                        href="/travel-destinations/movies"
                        class="rounded-xl border border-border bg-background px-4 py-3 text-sm font-medium transition hover:bg-accent"
                        >Filmid (API)</Link
                    >
                </div>
            </main>
        </div>
    </div>
</template>
