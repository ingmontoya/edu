<script setup lang="ts">
interface Action {
  label: string
  to?: string
  onClick?: () => void
  icon?: string
  color?: string
}

interface Props {
  icon: string
  title: string
  description?: string
  action?: Action
  note?: string
}

const props = defineProps<Props>()
</script>

<template>
  <div class="py-16 flex flex-col items-center gap-4 text-center">
    <div class="w-16 h-16 rounded-2xl bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center">
      <UIcon :name="props.icon" class="w-8 h-8 text-neutral-400 dark:text-neutral-500" />
    </div>
    <p class="text-base font-semibold text-highlighted">
      {{ props.title }}
    </p>
    <p v-if="props.description" class="text-sm text-muted max-w-xs leading-relaxed">
      {{ props.description }}
    </p>
    <p
      v-if="props.note"
      class="text-xs text-muted bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-2 max-w-sm"
    >
      {{ props.note }}
    </p>
    <UButton
      v-else-if="props.action"
      :color="(props.action.color as any) ?? 'primary'"
      variant="solid"
      size="sm"
      :to="props.action.to"
      :icon="props.action.icon"
      :label="props.action.label"
      @click="props.action.onClick"
    />
  </div>
</template>
