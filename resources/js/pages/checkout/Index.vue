<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  payment_method: 'mock',
})

const submit = () => {
  form.post('/checkout/pay')
}
</script>

<template>
  <Head title="Checkout" />
  <AppLayout>
    <div class="mx-auto max-w-xl p-6 space-y-4">
      <h1 class="text-2xl font-semibold">Maksmine</h1>

      <form @submit.prevent="submit" class="space-y-3">
        <div>
          <label class="mb-1 block text-sm">Eesnimi</label>
          <input v-model="form.first_name" class="w-full rounded border px-3 py-2" />
          <p v-if="form.errors.first_name" class="text-sm text-red-600">{{ form.errors.first_name }}</p>
        </div>

        <div>
          <label class="mb-1 block text-sm">Perenimi</label>
          <input v-model="form.last_name" class="w-full rounded border px-3 py-2" />
          <p v-if="form.errors.last_name" class="text-sm text-red-600">{{ form.errors.last_name }}</p>
        </div>

        <div>
          <label class="mb-1 block text-sm">E-post</label>
          <input v-model="form.email" type="email" class="w-full rounded border px-3 py-2" />
          <p v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <div>
          <label class="mb-1 block text-sm">Telefon</label>
          <input v-model="form.phone" class="w-full rounded border px-3 py-2" />
          <p v-if="form.errors.phone" class="text-sm text-red-600">{{ form.errors.phone }}</p>
        </div>

        <div>
          <label class="mb-1 block text-sm">Makseviis</label>
          <select v-model="form.payment_method" class="w-full rounded border px-3 py-2">
            <option value="mock">Mock Payment</option>
            <option value="stripe">Stripe (hiljem)</option>
          </select>
        </div>

        <button :disabled="form.processing" class="rounded border px-3 py-2">
          {{ form.processing ? 'Töötlen...' : 'Maksa' }}
        </button>
      </form>
    </div>
  </AppLayout>
</template>