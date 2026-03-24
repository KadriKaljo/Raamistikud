<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

type CartItem = {
  product_id: number
  name: string
  price: number
  quantity: number
  line_total: number
}

const props = defineProps<{
  items: CartItem[]
  total: number
}>()

const updateQty = (productId: number, quantity: number) => {
  router.patch(`/cart/${productId}`, { quantity }, { preserveScroll: true })
}

const removeItem = (productId: number) => {
  router.delete(`/cart/${productId}`, { preserveScroll: true })
}
</script>

<template>
  <Head title="Cart" />
  <AppLayout>
    <div class="mx-auto max-w-4xl p-6 space-y-4">
      <h1 class="text-2xl font-semibold">Ostukorv</h1>

      <div v-if="props.items.length === 0" class="text-muted-foreground">
        Korv on tühi.
      </div>

      <div v-else class="space-y-3">
        <div v-for="item in props.items" :key="item.product_id" class="rounded border p-3 flex items-center justify-between gap-3">
          <div>
            <p class="font-medium">{{ item.name }}</p>
            <p class="text-sm text-muted-foreground">{{ item.price }} € / tk</p>
          </div>

          <div class="flex items-center gap-2">
            <input
              :value="item.quantity"
              type="number"
              min="1"
              class="w-20 rounded border px-2 py-1"
              @change="updateQty(item.product_id, Number(($event.target as HTMLInputElement).value))"
            />
            <p class="w-24 text-right">{{ item.line_total.toFixed(2) }} €</p>
            <button class="text-red-600 underline" @click="removeItem(item.product_id)">Eemalda</button>
          </div>
        </div>

        <div class="text-right font-semibold">Kokku: {{ props.total.toFixed(2) }} €</div>

        <div class="flex justify-end gap-3">
          <Link href="/products" class="rounded border px-3 py-2">Tagasi tooteid valima</Link>
          <Link href="/checkout" class="rounded border px-3 py-2">Mine maksma</Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>