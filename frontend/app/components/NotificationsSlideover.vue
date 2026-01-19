<script setup lang="ts">
const { isNotificationsSlideoverOpen } = useDashboard()

// Replace with your notifications data source
const notifications = ref<{ id: number; title: string; body: string; date: string }[]>([])
</script>

<template>
  <USlideover
    v-model:open="isNotificationsSlideoverOpen"
    title="Notifications"
  >
    <template #body>
      <div v-if="notifications.length === 0" class="text-center py-8 text-muted">
        <UIcon name="i-lucide-bell-off" class="size-12 mx-auto mb-4 text-dimmed" />
        <p>No notifications</p>
      </div>

      <div
        v-for="notification in notifications"
        :key="notification.id"
        class="px-3 py-2.5 rounded-md hover:bg-elevated/50 flex items-center gap-3 relative -mx-3 first:-mt-3 last:-mb-3"
      >
        <div class="text-sm flex-1">
          <p class="text-highlighted font-medium">{{ notification.title }}</p>
          <p class="text-dimmed">{{ notification.body }}</p>
        </div>
      </div>
    </template>
  </USlideover>
</template>
