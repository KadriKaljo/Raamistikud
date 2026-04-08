<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

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

const page = usePage()
const flashSuccess = computed(() => (page.props.flash as { success?: string } | undefined)?.success)
const flashError = computed(() => (page.props.flash as { error?: string } | undefined)?.error)
const flashInfo = computed(() => (page.props.flash as { info?: string } | undefined)?.info)

const checkoutForm = useForm({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  payment_method: 'mock',
})

const updateQty = (productId: number, quantity: number) => {
  router.patch(`/cart/${productId}`, { quantity }, { preserveScroll: true })
}

const removeItem = (productId: number) => {
  router.delete(`/cart/${productId}`, { preserveScroll: true })
}

const submitCheckout = () => {
  checkoutForm.post('/checkout/pay')
}
</script>

<template>
  <Head title="Ostukorv" />
  <AppLayout>
    <div class="mx-auto max-w-5xl space-y-5 p-4 md:p-6">
      <section class="rounded-2xl border border-sky-200/50 bg-gradient-to-br from-sky-50/80 via-card to-blue-50/35 p-5 shadow-sm dark:border-sky-900/40 dark:from-sky-950/30 dark:via-card dark:to-blue-950/20">
        <h1 class="text-2xl font-semibold tracking-tight">Ostukorv</h1>
      </section>
      <p v-if="flashSuccess" class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">{{ flashSuccess }}</p>
      <p v-if="flashError" class="rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ flashError }}</p>
      <p v-if="flashInfo" class="rounded border border-sky-200 bg-sky-50 px-3 py-2 text-sm text-sky-800">{{ flashInfo }}</p>

      <div v-if="props.items.length === 0" class="rounded-2xl border border-border/60 bg-card/80 p-8 text-center text-muted-foreground">
        Korv on tühi.
      </div>

      <div v-else class="grid gap-5 lg:grid-cols-[1fr_340px]">
        <section class="space-y-3">
          <div v-for="item in props.items" :key="item.product_id" class="flex items-center justify-between gap-3 rounded-xl border border-border/70 bg-card/90 p-4 shadow-sm dark:bg-card/60">
            <div class="min-w-0">
              <p class="truncate font-medium">{{ item.name }}</p>
              <p class="text-sm text-muted-foreground">{{ Number(item.price).toFixed(2) }} € / tk</p>
            </div>

            <div class="flex items-center gap-2">
              <input
                :value="item.quantity"
                type="number"
                min="1"
                class="w-20 rounded-lg border border-border bg-background px-2 py-1.5 text-sm"
                @change="updateQty(item.product_id, Number(($event.target as HTMLInputElement).value))"
              />
              <p class="w-24 text-right text-sm font-semibold">{{ item.line_total.toFixed(2) }} €</p>
              <button class="text-sm font-medium text-red-600 underline-offset-2 hover:underline" @click="removeItem(item.product_id)">Eemalda</button>
            </div>
          </div>

          <div class="flex justify-start gap-3 pt-1">
            <Link href="/products" class="rounded-lg border border-border bg-background px-3 py-2 text-sm transition hover:bg-muted">
              ← Tagasi tooteid valima
            </Link>
          </div>
        </section>

        <aside class="h-fit space-y-4 rounded-2xl border border-border/70 bg-card/90 p-4 shadow-sm dark:bg-card/60">
          <p class="text-sm text-muted-foreground">Kokkuvõte</p>
          <p class="mt-1 text-2xl font-semibold">{{ props.total.toFixed(2) }} €</p>
          <p class="mt-2 text-xs text-muted-foreground">Tarne ja maksud arvutatakse maksmisel.</p>

          <form class="space-y-3 border-t border-border/60 pt-4" @submit.prevent="submitCheckout">
            <p class="text-sm font-medium">Maksmise andmed</p>
            <div class="grid gap-2 sm:grid-cols-2">
              <div>
                <input v-model="checkoutForm.first_name" placeholder="Eesnimi" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm" />
                <p v-if="checkoutForm.errors.first_name" class="mt-1 text-xs text-red-600">{{ checkoutForm.errors.first_name }}</p>
              </div>
              <div>
                <input v-model="checkoutForm.last_name" placeholder="Perenimi" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm" />
                <p v-if="checkoutForm.errors.last_name" class="mt-1 text-xs text-red-600">{{ checkoutForm.errors.last_name }}</p>
              </div>
            </div>
            <div>
              <input v-model="checkoutForm.email" type="email" placeholder="E-post" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm" />
              <p v-if="checkoutForm.errors.email" class="mt-1 text-xs text-red-600">{{ checkoutForm.errors.email }}</p>
            </div>
            <div>
              <input v-model="checkoutForm.phone" placeholder="Telefon" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm" />
              <p v-if="checkoutForm.errors.phone" class="mt-1 text-xs text-red-600">{{ checkoutForm.errors.phone }}</p>
            </div>
            <div>
              <select v-model="checkoutForm.payment_method" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm">
                <option value="mock">Mock (test)</option>
                <option value="stripe">Stripe</option>
              </select>
              <p v-if="checkoutForm.errors.payment_method" class="mt-1 text-xs text-red-600">{{ checkoutForm.errors.payment_method }}</p>
            </div>
            <button :disabled="checkoutForm.processing" class="inline-flex w-full items-center justify-center rounded-lg border border-border bg-background px-3 py-2 text-sm font-medium transition hover:bg-muted disabled:opacity-60">
              {{ checkoutForm.processing ? 'Töötlen...' : 'Maksa' }}
            </button>
          </form>
        </aside>
      </div>
    </div>
  </AppLayout>
</template>