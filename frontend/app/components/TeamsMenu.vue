<script setup lang="ts">
defineProps<{
  collapsed?: boolean
}>()
</script>

<template>
  <NuxtLink to="/" class="flex items-center gap-2 p-2">
    <div class="h-8 w-8 bg-primary-600 rounded-lg flex items-center justify-center shrink-0">
      <UIcon name="i-lucide-layout-dashboard" class="size-5 text-white" />
    </div>
    <span v-if="!collapsed" class="font-semibold text-sm truncate">App Template</span>
  </NuxtLink>
</template>
