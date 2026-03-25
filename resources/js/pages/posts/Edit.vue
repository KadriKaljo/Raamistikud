<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import { index, update } from '@/routes/posts';
import type { BreadcrumbItem } from '@/types';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Pencil } from 'lucide-vue-next';

const props = defineProps<{
    authors: Record<number, string>;
    post: {
        id: number;
        title: string;
        description: string;
        author_id: number;
        published: boolean;
        created_at_formatted?: string;
        updated_at_formatted?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Blogi', href: index().url },
    { title: props.post.title, href: `/posts/${props.post.id}/edit` },
];

const form = useForm<{
    title: string;
    description: string;
    author_id: number;
    published: boolean;
}>({
    author_id: props.post.author_id,
    title: props.post.title,
    description: props.post.description,
    published: props.post.published,
});

const submit = () => {
    form.put(update.url(props.post.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Muuda: ${post.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-2xl space-y-6 p-4 md:p-6">
            <div
                class="rounded-2xl border border-amber-200/50 bg-gradient-to-br from-amber-50/80 via-card to-orange-50/35 p-6 shadow-sm dark:border-amber-900/40 dark:from-amber-950/25 dark:via-card dark:to-orange-950/15"
            >
                <div class="flex items-start gap-3">
                    <span
                        class="inline-flex rounded-xl bg-amber-500/15 p-2.5 text-amber-800 ring-1 ring-amber-500/20 dark:bg-amber-400/10 dark:text-amber-200"
                    >
                        <Pencil class="size-6" aria-hidden="true" />
                    </span>
                    <div>
                        <h1 class="text-xl font-semibold tracking-tight md:text-2xl">Muuda postitust</h1>
                        <p class="mt-1 line-clamp-2 text-sm text-muted-foreground">{{ post.title }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-border/60 bg-card/90 p-6 shadow-sm dark:bg-card/50 md:p-8">
                <form class="flex flex-col gap-5" @submit.prevent="submit">
                    <div>
                        <Label for="title">Pealkiri</Label>
                        <Input id="title" v-model="form.title" class="mt-1.5" />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <div>
                        <Label for="author_id">Autor</Label>
                        <Select v-model="form.author_id">
                            <SelectTrigger id="author_id" class="mt-1.5">
                                <SelectValue placeholder="Vali autor" />
                            </SelectTrigger>
                            <SelectContent class="w-(--reka-select-trigger-width)">
                                <SelectGroup>
                                    <SelectItem v-for="(name, id) in authors" :key="id" :value="Number(id)">
                                        {{ name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>

                    <div>
                        <Label for="description">Sisu</Label>
                        <Textarea id="description" rows="10" v-model="form.description" class="mt-1.5 min-h-[10rem] resize-y" />
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-4 rounded-xl border border-border/60 bg-muted/20 px-4 py-3">
                        <Label for="published" class="cursor-pointer">Avaldatud</Label>
                        <Switch id="published" v-model="form.published" />
                    </div>

                    <div class="rounded-lg border border-border/40 bg-muted/15 px-4 py-3 text-sm text-muted-foreground">
                        <p>Loodud: {{ props.post.created_at_formatted }}</p>
                        <p>Viimati muudetud: {{ props.post.updated_at_formatted }}</p>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <Button type="submit" :disabled="form.processing">Salvesta muudatused</Button>
                        <Button type="button" variant="outline" as-child>
                            <Link :href="index().url">Loobu</Link>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
