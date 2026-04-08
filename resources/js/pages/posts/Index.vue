<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import PaginationContent from '@/components/ui/pagination/PaginationContent.vue';
import PaginationEllipsis from '@/components/ui/pagination/PaginationEllipsis.vue';
import PaginationFirst from '@/components/ui/pagination/PaginationFirst.vue';
import PaginationItem from '@/components/ui/pagination/PaginationItem.vue';
import PaginationLast from '@/components/ui/pagination/PaginationLast.vue';
import PaginationNext from '@/components/ui/pagination/PaginationNext.vue';
import PaginationPrevious from '@/components/ui/pagination/PaginationPrevious.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { destroy, edit, show, index } from '@/routes/posts';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { MoreVertical, Plus } from 'lucide-vue-next';

// Breadcrumbs for layout navigation
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Blogi',
    href: index().url,
  },
];

// Type definitions
interface PaginationLink {
  url: string | null;
  label: string;
  page?: number | null;
  active: boolean;
}

interface PaginatedResponse {
  current_page: number;
  data: Post[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: PaginationLink[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

type Comment = {
  id: number;
  post_id: number;
  user_id: number;
  content: string;
  created_at_formatted: string;
  updated_at_formatted: string;
  user: User;
};

type User = {
  id: number;
  name: string;
  email: string;
};


export type Post = {
  id: number;
  title: string;
  description: string;
  author_id: number;
  published: boolean;
  created_at: string;
  updated_at: string;
  created_at_formatted: string;
  updated_at_formatted: string;
  author: {
    id: number;
    first_name: string;
    last_name: string;
  };
  comments?: Comment[];
};


defineProps<{
  posts: PaginatedResponse;
}>();
const deletePost = (postId: number) => {
  if (!confirm('Kustutada see postitus?')) return;
  router.delete(destroy.url(postId), {
    preserveScroll: true,
    onError: () => alert('Postitust ei saanud kustutada.'),
  });
};

</script>

<template>

  <Head title="Blogi" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div
      class="mx-auto flex h-full w-full max-w-6xl flex-col gap-6 overflow-x-auto rounded-2xl bg-gradient-to-b from-muted/30 via-background to-background p-4 md:p-6 dark:from-muted/10"
    >
      <div
        class="rounded-2xl border border-violet-200/60 bg-gradient-to-br from-violet-50/90 via-card to-fuchsia-50/40 p-5 shadow-sm dark:border-violet-900/40 dark:from-violet-950/35 dark:via-card dark:to-fuchsia-950/20"
      >
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <h1 class="text-xl font-semibold tracking-tight md:text-2xl">Blogi postitused</h1>
            <p class="mt-1 text-sm text-muted-foreground">Loetelu, muutmine ja kustutamine.</p>
          </div>
          <Button as-child size="sm" class="rounded-xl">
            <Link href="/posts/create">
              <Plus class="mr-1.5 size-4" />
              Uus postitus
            </Link>
          </Button>
        </div>
      </div>

      <div class="overflow-hidden rounded-2xl border border-border/60 bg-card/90 shadow-sm dark:bg-card/60">
        <Table>
          <TableCaption class="text-muted-foreground">Viimased postitused (kuni 30 lehel).</TableCaption>
          <TableHeader>
            <TableRow class="bg-muted/50 hover:bg-muted/50">
              <TableHead class="w-[72px]">ID</TableHead>
              <TableHead>Pealkiri</TableHead>
              <TableHead>Autor</TableHead>
              <TableHead class="text-right">Loodud</TableHead>
              <TableHead class="text-right">Muudetud</TableHead>
              <TableHead class="text-right">Avaldatud</TableHead>
              <TableHead>
                <span class="sr-only">Toimingud</span>
              </TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-for="post in posts.data" :key="post.id" class="hover:bg-muted/20">
              <TableCell class="font-mono text-xs text-muted-foreground">{{ post.id }}</TableCell>
              <TableCell>
                <Link :href="show.url(post.id)" class="font-medium underline-offset-2 hover:underline">
                  {{ post.title }}
                </Link>
              </TableCell>
              <TableCell class="text-sm">
                {{ post.author.first_name }} {{ post.author.last_name }}
              </TableCell>
              <TableCell class="text-right text-sm text-muted-foreground">{{ post.created_at_formatted }}</TableCell>
              <TableCell class="text-right text-sm text-muted-foreground">{{ post.updated_at_formatted }}</TableCell>
              <TableCell class="text-right">
                <span
                  :class="
                    post.published
                      ? 'rounded-full bg-emerald-500/15 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:text-emerald-300'
                      : 'rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground'
                  "
                >
                  {{ post.published ? 'Jah' : 'Ei' }}
                </span>
              </TableCell>

              <TableCell>
                <div class="flex justify-end">
                  <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                      <Button size="icon" variant="ghost" class="size-8 rounded-lg">
                        <MoreVertical />
                      </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">
                      <DropdownMenuItem as-child>
                        <Link :href="show.url(post.id)">Vaata</Link>
                      </DropdownMenuItem>
                      <DropdownMenuItem as-child>
                        <Link :href="edit.url(post.id)">Muuda</Link>
                      </DropdownMenuItem>
                      <DropdownMenuSeparator />
                      <DropdownMenuItem class="text-destructive focus:text-destructive" @click="deletePost(post.id)">
                        Kustuta
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>

      <Pagination class="w-full" :page="posts.current_page" v-slot="{ page }" :total="posts.total"
        :items-per-page="posts.per_page" @update:page="(page) => router.get(index().url, { page: page })">
        <PaginationContent v-slot="{ items }" class="flex items-center gap-1">
          <PaginationFirst />
          <PaginationPrevious />

          <template v-for="(item, index) in items" :key="index">
            <PaginationItem v-if="item.type === 'page'" :value="item.value" as-child>
              <Button class="w-10 h-10 p-0" :variant="item.value === page ? 'default' : 'outline'">
                {{ item.value }}
              </Button>
            </PaginationItem>

            <PaginationEllipsis v-else :key="item.type" :index="index" />
          </template>

          <PaginationNext />
          <PaginationLast />
        </PaginationContent>
      </Pagination>
    </div>
  </AppLayout>
</template>
