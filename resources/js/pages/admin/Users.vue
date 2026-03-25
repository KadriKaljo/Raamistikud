<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Switch } from '@/components/ui/switch';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Shield } from 'lucide-vue-next';
import { computed } from 'vue';

type UserRow = {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
};

const props = defineProps<{
    users: UserRow[];
}>();

const page = usePage();
const currentUserId = computed(() => (page.props.auth?.user as { id?: number } | undefined)?.id);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Kasutajad (admin)', href: '/admin/users' },
];

function setAdmin(user: UserRow, value: boolean) {
    if (user.is_admin && !value) {
        const admins = props.users.filter((u) => u.is_admin).length;
        if (admins <= 1) {
            alert('Vähemalt üks administraator peab alles jääma.');
            return;
        }
    }
    router.patch(
        `/admin/users/${user.id}`,
        { is_admin: value },
        { preserveScroll: true },
    );
}
</script>

<template>
    <Head title="Kasutajad — admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto max-w-4xl space-y-6 p-4 md:p-6 lg:max-w-5xl"
        >
            <div
                class="rounded-2xl border border-violet-200/60 bg-gradient-to-br from-violet-50/90 via-card to-fuchsia-50/40 p-6 shadow-sm dark:border-violet-900/40 dark:from-violet-950/35 dark:via-card dark:to-fuchsia-950/20"
            >
                <div class="flex items-start gap-3">
                    <span
                        class="inline-flex rounded-xl bg-violet-500/15 p-2.5 text-violet-700 ring-1 ring-violet-500/20 dark:bg-violet-400/10 dark:text-violet-300"
                    >
                        <Shield class="size-6" aria-hidden="true" />
                    </span>
                    <div>
                        <h1 class="text-xl font-semibold tracking-tight md:text-2xl">Kasutajate õigused</h1>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Siin saad määrata, kes on <strong class="text-foreground">administraator</strong> (blogi
                            kommentaaride üldine haldus, kommentaaride kustutamine kõikjal). Tavalised kasutajad
                            näevad ainult oma kontot ja sisu.
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-border/60 bg-card/80 shadow-sm backdrop-blur-sm dark:bg-card/50">
                <Table>
                    <TableHeader>
                        <TableRow class="bg-muted/40 hover:bg-muted/40">
                            <TableHead>Nimi</TableHead>
                            <TableHead>E-post</TableHead>
                            <TableHead class="text-center">Admin</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="u in users" :key="u.id">
                            <TableCell class="font-medium">
                                {{ u.name }}
                                <span v-if="u.id === currentUserId" class="ml-2 text-xs font-normal text-muted-foreground"
                                    >(sina)</span
                                >
                            </TableCell>
                            <TableCell class="text-muted-foreground">{{ u.email }}</TableCell>
                            <TableCell>
                                <div class="flex justify-center">
                                    <Switch
                                        :checked="u.is_admin"
                                        @update:checked="(v: boolean) => setAdmin(u, v)"
                                    />
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <p class="text-center text-xs text-muted-foreground">
                Otse andmebaasis: <code class="rounded bg-muted px-1.5 py-0.5 font-mono text-[11px]">users.is_admin</code>
                — seda võid muuta ka SQL-i või php artisan tinkeriga, kui vaja.
            </p>
        </div>
    </AppLayout>
</template>
