<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'

definePageMeta({
  middleware: 'auth'
})

const auth = useAuthStore()
const toast = useToast()

const profileSchema = z.object({
  name: z.string().min(2, 'Muy corto'),
  email: z.string().email('Correo inválido')
})

type ProfileSchema = z.output<typeof profileSchema>

const profile = reactive<Partial<ProfileSchema>>({
  name: auth.user?.name || '',
  email: auth.user?.email || ''
})

async function onSubmit(event: FormSubmitEvent<ProfileSchema>) {
  // TODO: Implement API call to update profile
  toast.add({
    title: 'Éxito',
    description: 'Tu configuración ha sido actualizada.',
    icon: 'i-lucide-check',
    color: 'success'
  })
  console.log(event.data)
}
</script>

<template>
  <UForm
    id="settings"
    :schema="profileSchema"
    :state="profile"
    @submit="onSubmit"
  >
    <UPageCard
      title="Perfil"
      description="Administra la configuración de tu cuenta."
      variant="naked"
      orientation="horizontal"
      class="mb-4"
    >
      <UButton
        form="settings"
        label="Guardar cambios"
        color="neutral"
        type="submit"
        class="w-fit lg:ms-auto"
      />
    </UPageCard>

    <UPageCard variant="subtle">
      <UFormField
        name="name"
        label="Nombre"
        description="Tu nombre de usuario."
        required
        class="flex max-sm:flex-col justify-between items-start gap-4"
      >
        <UInput
          v-model="profile.name"
          autocomplete="off"
        />
      </UFormField>
      <USeparator />
      <UFormField
        name="email"
        label="Correo electrónico"
        description="Tu dirección de correo."
        required
        class="flex max-sm:flex-col justify-between items-start gap-4"
      >
        <UInput
          v-model="profile.email"
          type="email"
          autocomplete="off"
          disabled
        />
      </UFormField>
    </UPageCard>
  </UForm>
</template>
