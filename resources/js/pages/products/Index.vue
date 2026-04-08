<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

type Product = {
  id: number
  name: string
  description: string
  price: number
  image_url: string | null
  stock_quantity: number
}

const props = defineProps<{ products: Product[] }>()
const quantities = ref<Record<number, number>>({})
const query = ref('')
const sortBy = ref<'name' | 'price_asc' | 'price_desc'>('name')
const totalProducts = computed(() => props.products.length)
const filteredProducts = computed(() => {
  const q = query.value.trim().toLowerCase()
  let list = props.products.filter((p) =>
    q === '' ? true : p.name.toLowerCase().includes(q) || p.description.toLowerCase().includes(q),
  )
  if (sortBy.value === 'price_asc') {
    list = [...list].sort((a, b) => Number(a.price) - Number(b.price))
  } else if (sortBy.value === 'price_desc') {
    list = [...list].sort((a, b) => Number(b.price) - Number(a.price))
  } else {
    list = [...list].sort((a, b) => a.name.localeCompare(b.name, 'et'))
  }
  return list
})

const addToCart = (productId: number) => {
  const quantity = quantities.value[productId] || 1
  router.post('/cart', { product_id: productId, quantity }, { preserveScroll: true })
}
</script>

<template>
  <Head title="Tooted" />
  <AppLayout>
    <div class="mx-auto max-w-6xl space-y-6 p-4 md:p-6">
      <section class="rounded-2xl border border-amber-200/50 bg-gradient-to-br from-amber-50/80 via-card to-orange-50/40 p-6 shadow-sm dark:border-amber-900/40 dark:from-amber-950/30 dark:via-card dark:to-orange-950/20">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <h1 class="text-2xl font-semibold tracking-tight">Tooted</h1>
            <p class="mt-1 text-sm text-muted-foreground">Vali tooted, määra kogus ja lisa ostukorvi.</p>
          </div>
          <div class="rounded-xl border border-border/60 bg-background/80 px-4 py-2 text-sm">
            Kokku tooteid: <span class="font-semibold">{{ totalProducts }}</span>
          </div>
        </div>
        <div class="mt-4 grid gap-3 sm:grid-cols-[1fr_220px]">
          <input
            v-model="query"
            type="text"
            placeholder="Otsi toodet nime või kirjelduse järgi..."
            class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm"
          />
          <select v-model="sortBy" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm">
            <option value="name">Sorteeri: nimi (A-Z)</option>
            <option value="price_asc">Hind: odavam enne</option>
            <option value="price_desc">Hind: kallim enne</option>
          </select>
        </div>
      </section>

      <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <article v-for="p in filteredProducts" :key="p.id" class="group overflow-hidden rounded-2xl border border-border/60 bg-card/90 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-card/60">
           <img
                :src="`https://picsum.photos/seed/product-${p.id}/600/400`"
                :alt="p.name"
                class="h-44 w-full object-cover transition duration-300 group-hover:scale-[1.02]"
            />
          <div class="space-y-3 p-4">
            <div class="flex items-start justify-between gap-2">
              <h2 class="font-semibold leading-snug">{{ p.name }}</h2>
              <span class="shrink-0 rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:text-emerald-300">
                {{ Number(p.price).toFixed(2) }} €
              </span>
            </div>
            <p class="line-clamp-3 text-sm text-muted-foreground">{{ p.description }}</p>
            <p
              :class="
                p.stock_quantity > 0
                  ? 'inline-flex rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-medium text-emerald-800 dark:text-emerald-300'
                  : 'inline-flex rounded-full bg-red-500/15 px-2.5 py-1 text-xs font-medium text-red-700 dark:text-red-300'
              "
            >
              {{ p.stock_quantity > 0 ? `Laos: ${p.stock_quantity} tk` : 'Läbi müüdud' }}
            </p>

            <div class="flex items-center gap-2 pt-1">
              <input
                v-model.number="quantities[p.id]"
                type="number"
                min="1"
                :max="p.stock_quantity"
                class="w-20 rounded-lg border border-border bg-background px-2 py-1.5 text-sm"
                placeholder="1"
              />
              <button
                class="rounded-lg border border-border bg-background px-3 py-1.5 text-sm font-medium transition hover:bg-muted disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="p.stock_quantity <= 0"
                @click="addToCart(p.id)"
              >
                Lisa korvi
              </button>
            </div>
          </div>
        </article>
      </div>
      <p v-if="filteredProducts.length === 0" class="rounded-xl border border-border/60 bg-card/60 p-5 text-center text-sm text-muted-foreground">
        Otsingule vastavaid tooteid ei leitud.
      </p>
      <div class="flex justify-end">
        <Link href="/cart" class="rounded-lg border border-border bg-background px-4 py-2 text-sm font-medium transition hover:bg-muted">
          Vaata ostukorvi →
        </Link>
      </div>
    </div>
  </AppLayout>
</template>