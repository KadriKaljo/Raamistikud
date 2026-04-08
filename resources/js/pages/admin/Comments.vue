<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { dashboard } from '@/routes';
import { index as postsIndex, show as postShow } from '@/routes/posts';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { MessageSquare } from 'lucide-vue-next';

type CommentRow = {
    id: number;
    content: string;
    created_at_formatted: string;
    post: { id: number; title: string };
    user: { id: number; name: string; email: string };
};

defineProps<{
    comments: {
        data: CommentRow[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        links: { url: string | null; label: string; active: boolean }[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Kommentaarid (admin)', href: '/admin/comments' },
];

function deleteComment(id: number) {
    if (!confirm('Kustutada see kommentaar?')) return;
    router.delete(`/comments/${id}`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Kommentaaride haldus" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-5xl space-y-6 p-4 md:p-6">
            <div
                class="rounded-2xl border border-sky-200/50 bg-gradient-to-br from-sky-50/90 via-card to-blue-50/40 p-6 shadow-sm dark:border-sky-900/40 dark:from-sky-950/35 dark:via-card dark:to-blue-950/25"
            >
                <div class="flex items-start gap-3">
                    <span
                        class="inline-flex rounded-xl bg-sky-500/15 p-2.5 text-sky-700 ring-1 ring-sky-500/20 dark:bg-sky-400/10 dark:text-sky-300"
                    >
                        <MessageSquare class="size-6" aria-hidden="true" />
                    </span>
                    <div>
                        <h1 class="text-xl font-semibold tracking-tight md:text-2xl">Kommentaaride haldus</h1>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Ülevaade kõikidest kommentaaridest. Administraator võib kustutada mis tahes kommentaari.
                            Postituse vaates saavad kasutajad kustutada ka oma kommentaare.
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-border/60 bg-card/90 shadow-sm dark:bg-card/60">
                <Table>
                    <TableHeader>
                        <TableRow class="bg-muted/50 hover:bg-muted/50">
                            <TableHead class="w-14">ID</TableHead>
                            <TableHead>Postitus</TableHead>
                            <TableHead>Kasutaja</TableHead>
                            <TableHead class="max-w-md">Kommentaar</TableHead>
                            <TableHead>Aeg</TableHead>
                            <TableHead class="text-right">Toiming</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="c in comments.data" :key="c.id" class="hover:bg-muted/25">
                            <TableCell class="font-mono text-xs text-muted-foreground">{{ c.id }}</TableCell>
                            <TableCell>
                                <Link
                                    :href="postShow(c.post.id).url"
                                    class="font-medium text-sky-800 underline-offset-2 hover:underline dark:text-sky-300"
                                >
                                    {{ c.post.title }}
                                </Link>
                            </TableCell>
                            <TableCell class="text-sm">
                                <span class="font-medium">{{ c.user.name }}</span>
                                <span class="block text-xs text-muted-foreground">{{ c.user.email }}</span>
                            </TableCell>
                            <TableCell class="max-w-md truncate text-sm">{{ c.content }}</TableCell>
                            <TableCell class="whitespace-nowrap text-sm text-muted-foreground">
                                {{ c.created_at_formatted }}
                            </TableCell>
                            <TableCell class="text-right">
                                <Button type="button" variant="outline" size="sm" class="border-red-200 text-red-600 hover:bg-red-50 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/40" @click="deleteComment(c.id)">
                                    Kustuta
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <p v-if="comments.data.length === 0" class="p-8 text-center text-sm text-muted-foreground">
                    Kommentaare pole.
                </p>
            </div>

            <div v-if="comments.last_page > 1" class="flex flex-wrap justify-center gap-1.5 text-sm">
                <template v-for="link in comments.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="rounded-lg px-3 py-1.5 transition-colors"
                        :class="link.active ? 'bg-primary text-primary-foreground' : 'border border-border hover:bg-muted'"
                        preserve-scroll
                    >
                        <span v-html="link.label" />
                    </Link>
                    <span v-else class="px-2 py-1.5 text-muted-foreground" v-html="link.label" />
                </template>
            </div>

            <div class="flex justify-center border-t border-border/60 pt-6">
                <Link
                    :href="postsIndex().url"
                    class="text-sm font-medium text-muted-foreground underline-offset-2 transition hover:text-foreground hover:underline"
                >
                    ← Blogi postituste nimekirja
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
