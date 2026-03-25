<script setup lang="ts">
import type { Post } from './Index.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, show } from '@/routes/posts';
import type { BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { add } from '@/routes/comments';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { computed } from 'vue';
import { BookOpen } from 'lucide-vue-next';

const props = defineProps<{ post: Post }>();

const page = usePage();
const authUser = computed(() => page.props.auth?.user as { id: number; is_admin?: boolean } | undefined | null);

function canDeleteComment(commentUserId: number): boolean {
    const u = authUser.value;
    if (!u) return false;
    return Boolean(u.is_admin) || u.id === commentUserId;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Blogi', href: index().url },
    { title: props.post.title, href: show(props.post.id).url },
];

const form = useForm({
    content: '',
});

const submit = () => {
    form.post(add(props.post.id).url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const deleteComment = (id: number) => {
    if (!confirm('Kustuta kommentaar?')) return;

    router.delete(`/comments/${id}`, {
        preserveScroll: true,
        onError: () => alert('Kommentaari kustutamine ebaõnnestus.'),
    });
};
</script>

<template>
    <Head :title="post.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto max-w-2xl space-y-8 p-4 md:p-6"
        >
            <article
                class="rounded-2xl border border-violet-200/40 bg-gradient-to-b from-card to-muted/20 p-6 shadow-sm dark:border-violet-900/30 dark:from-card dark:to-muted/10 md:p-8"
            >
                <div class="mb-4 inline-flex rounded-xl bg-violet-500/10 p-2 text-violet-700 dark:text-violet-300">
                    <BookOpen class="size-5" aria-hidden="true" />
                </div>
                <h1 class="text-2xl font-semibold tracking-tight md:text-3xl">{{ post.title }}</h1>

                <p class="mt-3 text-sm text-muted-foreground">
                    <span v-if="post.author">{{ post.author.first_name }} {{ post.author.last_name }} · </span>
                    {{ post.created_at_formatted }}
                </p>

                <div class="mt-6 whitespace-pre-wrap border-t border-border/60 pt-6 text-[15px] leading-relaxed text-foreground">
                    {{ post.description }}
                </div>
            </article>

            <section class="space-y-5 rounded-2xl border border-border/60 bg-card/80 p-6 shadow-sm dark:bg-card/40">
                <h2 class="text-lg font-semibold tracking-tight">Kommentaarid</h2>

                <form id="comment-form" class="space-y-3" @submit.prevent="submit">
                    <Textarea v-model="form.content" placeholder="Lisa kommentaar…" rows="3" class="resize-y min-h-[5rem]" />
                    <InputError :message="form.errors.content" />
                    <Button type="submit" :disabled="form.processing">Saada kommentaar</Button>
                </form>

                <ul class="space-y-3 pt-2">
                    <li
                        v-for="comment in post.comments"
                        :key="comment.id"
                        class="rounded-xl border border-border/80 bg-muted/20 p-4 dark:bg-muted/10"
                    >
                        <p class="mb-2 text-sm text-muted-foreground">
                            <span class="font-medium text-foreground">{{ comment.user.name }}</span>
                            · {{ comment.created_at_formatted }}
                        </p>
                        <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ comment.content }}</p>
                        <button
                            v-if="canDeleteComment(comment.user_id)"
                            type="button"
                            class="mt-3 text-sm font-medium text-red-600 underline-offset-2 hover:underline dark:text-red-400"
                            @click="deleteComment(comment.id)"
                        >
                            Kustuta
                        </button>
                    </li>
                </ul>

                <p v-if="!post.comments?.length" class="text-sm text-muted-foreground">Kommentaare veel pole — ole esimene.</p>
            </section>
        </div>
    </AppLayout>
</template>
