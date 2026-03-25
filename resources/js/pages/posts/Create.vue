<script setup lang="ts">
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index, store } from '@/routes/posts';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import Switch from '@/components/ui/switch/Switch.vue';
import Button from '@/components/ui/button/Button.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import InputError from '@/components/InputError.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Link } from '@inertiajs/vue3';
import { PenLine } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Blogi', href: index().url },
    { title: 'Uus postitus', href: create().url },
];

const props = defineProps<{ authors: Record<number, string> }>();

const firstAuthorId = Object.keys(props.authors)[0] ?? '';

const form = useForm({
    title: '',
    description: '',
    author_id: firstAuthorId === '' ? '' : Number(firstAuthorId),
    published: false,
});

const submit = () => {
    form.post(store().url);
};
</script>

<template>
    <Head title="Uus postitus" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-2xl space-y-6 p-4 md:p-6">
            <div
                class="rounded-2xl border border-emerald-200/50 bg-gradient-to-br from-emerald-50/80 via-card to-teal-50/40 p-6 shadow-sm dark:border-emerald-900/40 dark:from-emerald-950/30 dark:via-card dark:to-teal-950/20"
            >
                <div class="flex items-start gap-3">
                    <span
                        class="inline-flex rounded-xl bg-emerald-500/15 p-2.5 text-emerald-700 ring-1 ring-emerald-500/20 dark:bg-emerald-400/10 dark:text-emerald-300"
                    >
                        <PenLine class="size-6" aria-hidden="true" />
                    </span>
                    <div>
                        <h1 class="text-xl font-semibold tracking-tight md:text-2xl">Uus postitus</h1>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Lisa pealkiri, sisu ja vali autor. Märgi „Avaldatud“, kui postitus peaks kohe nähtav olema.
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-border/60 bg-card/90 p-6 shadow-sm dark:bg-card/50 md:p-8">
                <form class="space-y-5" @submit.prevent="submit">
                    <div>
                        <Label for="title">Pealkiri</Label>
                        <Input id="title" class="mt-1.5" name="title" v-model="form.title" />
                        <InputError :message="form.errors.title" />
                    </div>
                    <div>
                        <Label for="description">Sisu</Label>
                        <Textarea id="description" v-model="form.description" class="mt-1.5 min-h-[10rem] resize-y" rows="10" />
                        <InputError :message="form.errors.description" />
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
                        <InputError :message="form.errors.author_id" />
                    </div>

                    <div class="flex items-center gap-3 rounded-xl border border-border/60 bg-muted/20 px-4 py-3">
                        <Switch id="published" v-model="form.published" />
                        <Label for="published" class="cursor-pointer text-sm font-normal">Avaldatud</Label>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <Button type="submit" :disabled="form.processing">Salvesta</Button>
                        <Button type="button" variant="outline" as-child>
                            <Link :href="index().url">Tühista</Link>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
