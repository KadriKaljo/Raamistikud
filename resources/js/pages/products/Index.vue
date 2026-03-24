<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

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

const addToCart = (productId: number) => {
  const quantity = quantities.value[productId] || 1
  router.post('/cart', { product_id: productId, quantity }, { preserveScroll: true })
}
</script>

<template>
  <Head title="Products" />
  <AppLayout>
    <div class="mx-auto max-w-6xl p-6">
      <h1 class="mb-6 text-2xl font-semibold">Tooted</h1>

      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="p in props.products" :key="p.id" class="rounded-xl border p-4 space-y-3">
           <img
                :src="`https://picsum.photos/seed/product-${p.id}/600/400`"
                :alt="p.name"
                class="h-40 w-full rounded-md object-cover"
            />
          <h2 class="font-semibold">{{ p.name }}</h2>
          <p class="text-sm text-muted-foreground">{{ p.description }}</p>
          <p class="font-medium">{{ p.price }} €</p>

          <div class="flex items-center gap-2">
            <input
              v-model.number="quantities[p.id]"
              type="number"
              min="1"
              :max="p.stock_quantity"
              class="w-20 rounded border px-2 py-1"
              placeholder="1"
            />
            <button class="rounded border px-3 py-1" @click="addToCart(p.id)">Lisa korvi</button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>