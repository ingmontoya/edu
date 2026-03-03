<script setup lang="ts">
import * as z from 'zod'
import type { FormError } from '@nuxt/ui'

const passwordSchema = z.object({
  current: z.string().min(8, 'Debe tener al menos 8 caracteres'),
  new: z.string().min(8, 'Debe tener al menos 8 caracteres')
})

type PasswordSchema = z.output<typeof passwordSchema>

const password = reactive<Partial<PasswordSchema>>({
  current: undefined,
  new: undefined
})

const validate = (state: Partial<PasswordSchema>): FormError[] => {
  const errors: FormError[] = []
  if (state.current && state.new && state.current === state.new) {
    errors.push({ name: 'new', message: 'Las contraseñas deben ser diferentes' })
  }
  return errors
}
</script>

<template>
  <UPageCard
    title="Contraseña"
    description="Confirma tu contraseña actual antes de establecer una nueva."
    variant="subtle"
  >
    <UForm
      :schema="passwordSchema"
      :state="password"
      :validate="validate"
      class="flex flex-col gap-4 max-w-xs"
    >
      <UFormField name="current">
        <UInput
          v-model="password.current"
          type="password"
          placeholder="Contraseña actual"
          class="w-full"
        />
      </UFormField>

      <UFormField name="new">
        <UInput
          v-model="password.new"
          type="password"
          placeholder="Nueva contraseña"
          class="w-full"
        />
      </UFormField>

      <UButton label="Actualizar" class="w-fit" type="submit" />
    </UForm>
  </UPageCard>

  <UPageCard
    title="Cuenta"
    description="¿Ya no deseas usar nuestro servicio? Puedes eliminar tu cuenta aquí. Esta acción no es reversible. Toda la información relacionada con esta cuenta será eliminada permanentemente."
    class="bg-gradient-to-tl from-error/10 from-5% to-default"
  >
    <template #footer>
      <UButton label="Eliminar cuenta" color="error" />
    </template>
  </UPageCard>
</template>
