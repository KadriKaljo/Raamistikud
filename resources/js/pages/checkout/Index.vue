<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  payment_method: 'mock',
})

const page = usePage()
const flashSuccess = computed(() => (page.props.flash as { success?: string } | undefined)?.success)
const flashError = computed(() => (page.props.flash as { error?: string } | undefined)?.error)
const flashInfo = computed(() => (page.props.flash as { info?: string } | undefined)?.info)

const submit = () => {
  form.post('/checkout/pay')
}
</script>

<template>
  <Head title="Maksmine" />
  <AppLayout>
    <div class="mx-auto max-w-5xl space-y-5 p-4 md:p-6">
      <section class="rounded-2xl border border-violet-200/50 bg-gradient-to-br from-violet-50/90 via-card to-fuchsia-50/35 p-5 shadow-sm dark:border-violet-900/40 dark:from-violet-950/30 dark:via-card dark:to-fuchsia-950/20">
        <h1 class="text-2xl font-semibold tracking-tight">Maksmine</h1>
        <p class="mt-1 text-sm text-muted-foreground">Sisesta kontaktandmed ja vali makseviis.</p>
      </section>
      <p v-if="flashSuccess" class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">{{ flashSuccess }}</p>
      <p v-if="flashError" class="rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ flashError }}</p>
      <p v-if="flashInfo" class="rounded border border-sky-200 bg-sky-50 px-3 py-2 text-sm text-sky-800">{{ flashInfo }}</p>

      <div class="grid gap-5 lg:grid-cols-[1fr_300px]">
        <form @submit.prevent="submit" class="space-y-4 rounded-2xl border border-border/70 bg-card/90 p-5 shadow-sm dark:bg-card/60">
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label class="mb-1 block text-sm">Eesnimi</label>
              <input v-model="form.first_name" class="w-full rounded-lg border border-border bg-background px-3 py-2" />
              <p v-if="form.errors.first_name" class="text-sm text-red-600">{{ form.errors.first_name }}</p>
            </div>

            <div>
              <label class="mb-1 block text-sm">Perenimi</label>
              <input v-model="form.last_name" class="w-full rounded-lg border border-border bg-background px-3 py-2" />
              <p v-if="form.errors.last_name" class="text-sm text-red-600">{{ form.errors.last_name }}</p>
            </div>
          </div>

          <div>
            <label class="mb-1 block text-sm">E-post</label>
            <input v-model="form.email" type="email" class="w-full rounded-lg border border-border bg-background px-3 py-2" />
            <p v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="mb-1 block text-sm">Telefon</label>
            <input v-model="form.phone" class="w-full rounded-lg border border-border bg-background px-3 py-2" />
            <p v-if="form.errors.phone" class="text-sm text-red-600">{{ form.errors.phone }}</p>
          </div>

          <div>
            <label class="mb-1 block text-sm">Makseviis</label>
            <select v-model="form.payment_method" class="w-full rounded-lg border border-border bg-background px-3 py-2">
              <option value="mock">Mock (test)</option>
            <option value="stripe">Stripe</option>
            </select>
          </div>

          <button :disabled="form.processing" class="rounded-lg border border-border bg-background px-4 py-2 text-sm font-medium transition hover:bg-muted disabled:opacity-60">
            {{ form.processing ? 'Töötlen...' : 'Maksa' }}
          </button>
        </form>

        <aside class="h-fit rounded-2xl border border-border/70 bg-card/90 p-4 shadow-sm dark:bg-card/60">
          <p class="text-sm font-medium">Turvaline makse</p>
          <p class="mt-2 text-sm text-muted-foreground">
            Stripe makse suunab sind turvalisele lehele. Eduka makse korral tellimus kinnitatakse automaatselt.
          </p>
          <ul class="mt-3 space-y-2 text-xs text-muted-foreground">
            <li>• Edukas: korv tühjendatakse</li>
            <li>• Ebaõnnestunud/katkestatud: tooted jäävad korvi</li>
            <li>• Testimiseks saad valida Mock variandi</li>
          </ul>
        </aside>
      </div>
    </div>
  </AppLayout>
</template>