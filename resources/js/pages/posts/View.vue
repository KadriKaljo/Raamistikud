<script setup lang="ts">
import type { Post } from './Index.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, show } from '@/routes/posts';
import type { BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { add } from '@/routes/comments';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
const props = defineProps<{ post: Post }>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Posts', href: index().url },
  { title: props.post.title, href: show(props.post.id).url },
];

const form = useForm({
  content: '',
});

const submit = () => {
  form.post(add(props.post.id).url), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  }
};


</script>
<template>
  <AppLayout :breadcrumbs="breadcrumbs">
  <div class="max-w-2xl mx-auto p-6 space-y-4">
    <h1 class="text-2xl font-semibold">{{ post.title }}</h1>

    <p class="text-sm text-gray-500">
      <span v-if="post.author">By {{ post.author.first_name }} {{ post.author.last_name }} · </span>
      {{ post.created_at_formatted }}
    </p>

    <div class="whitespace-pre-wrap">
      {{ post.content }}
    </div>
      <div class="space-y-4">
        <h2 class="text-lg font-semibold">Comments</h2>
        <div class="pb-6">
          <form id="comment-form" @submit.prevent="submit">
            <Textarea v-model="form.content"></Textarea>
          </form>
          <div>
            <Button form="comment-form" type="submit">Submit</Button>
          </div>
        </div>
        <ul>
        <li v-for="comment in post.comments" :key="comment.id"  class="rounded-lg border bg-white/70 p-4 shadow-sm">
          <p class="mb-1 text-sm text-gray-600">{{ comment.user.name }} - {{ comment.created_at_formatted }}</p>
          {{comment.id}} - {{ comment.content }}
          <span class="text-sm text-gray-500">
          </span>
        </li>
    </ul>
  </div>  
  <div v-if="post.comments && post.comments.length === 0">
    <p>No comments yet.</p>
  </div>
</div>
</AppLayout>
</template>