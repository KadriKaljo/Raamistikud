<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps<{
  order: {
    id: number
    first_name: string
    last_name: string
    email: string
    phone: string
    payment_method: string
    payment_status: string
    total_amount: number
    created_at: string | null
  }
  items: Array<{
    product_id: number
    name: string
    quantity: number
    unit_price: number
    line_total: number
  }>
}>()
</script>

<template>
  <Head title="Tellimus kinnitatud" />
  <AppLayout>
    <div class="mx-auto max-w-3xl space-y-5 p-4 md:p-6">
      <section class="rounded-2xl border border-emerald-200/60 bg-gradient-to-br from-emerald-50/90 via-card to-teal-50/40 p-6 shadow-sm dark:border-emerald-900/40 dark:from-emerald-950/35 dark:via-card dark:to-teal-950/20">
        <h1 class="text-2xl font-semibold tracking-tight">Aitäh! Tellimus on kinnitatud.</h1>
        <p class="mt-1 text-sm text-muted-foreground">
          Tellimus #{{ order.id }} · Makstud: {{ order.total_amount.toFixed(2) }} €
        </p>
      </section>

      <section class="rounded-2xl border border-border/70 bg-card/90 p-4 shadow-sm dark:bg-card/60">
        <div class="space-y-2">
          <div v-for="item in items" :key="item.product_id" class="flex items-center justify-between rounded-xl border border-border/60 px-3 py-2">
            <div>
              <p class="font-medium">{{ item.name }}</p>
              <p class="text-xs text-muted-foreground">{{ item.quantity }} × {{ item.unit_price.toFixed(2) }} €</p>
            </div>
            <p class="text-sm font-semibold">{{ item.line_total.toFixed(2) }} €</p>
          </div>
        </div>
      </section>

      <div class="flex flex-wrap gap-3">
        <Link href="/products" class="rounded-lg border border-border bg-background px-4 py-2 text-sm font-medium transition hover:bg-muted">
          Tagasi poodi
        </Link>
        <Link href="/cart" class="rounded-lg border border-border bg-background px-4 py-2 text-sm font-medium transition hover:bg-muted">
          Ava ostukorv
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
