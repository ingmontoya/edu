<script setup lang="ts">
// TODO: Replace with your members data source
const members = ref<{ id: number; name: string; email: string; role: string }[]>([])
const q = ref('')

const filteredMembers = computed(() => {
  if (!q.value) return members.value
  return members.value.filter((member) => {
    return member.name.toLowerCase().includes(q.value.toLowerCase()) ||
           member.email.toLowerCase().includes(q.value.toLowerCase())
  })
})
</script>

<template>
  <div>
    <UPageCard
      title="Members"
      description="Manage team members."
      variant="naked"
      orientation="horizontal"
      class="mb-4"
    >
      <UButton
        label="Invite people"
        color="neutral"
        class="w-fit lg:ms-auto"
      />
    </UPageCard>

    <UPageCard variant="subtle">
      <UInput
        v-model="q"
        icon="i-lucide-search"
        placeholder="Search members..."
        class="w-full mb-4"
      />

      <div v-if="filteredMembers.length === 0" class="text-center py-8 text-muted">
        <UIcon name="i-lucide-users" class="size-12 mx-auto mb-4 text-dimmed" />
        <p>No members found</p>
      </div>

      <div v-for="member in filteredMembers" :key="member.id" class="flex items-center justify-between py-2">
        <div class="flex items-center gap-3">
          <UAvatar icon="i-lucide-user" size="sm" />
          <div>
            <p class="font-medium">{{ member.name }}</p>
            <p class="text-sm text-muted">{{ member.email }}</p>
          </div>
        </div>
        <UBadge :label="member.role" color="neutral" variant="subtle" />
      </div>
    </UPageCard>
  </div>
</template>
