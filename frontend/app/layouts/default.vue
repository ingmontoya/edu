<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui'

const auth = useAuthStore()
const institution = useInstitutionStore()
const { t } = useTerminology()
const open = ref(false)

onMounted(async () => {
  if (auth.isAuthenticated && !institution.institution) {
    await institution.fetch()
  }
})

watch(() => auth.isAuthenticated, async (authed) => {
  if (authed && !institution.institution) {
    await institution.fetch()
  }
})

const mainLinks = computed<NavigationMenuItem[]>(() => {
  const links: NavigationMenuItem[] = []

  // Student portal
  if (auth.isStudent) {
    links.push({
      label: 'Mi Portal',
      icon: 'i-lucide-layout-dashboard',
      to: '/student',
      onSelect: () => { open.value = false }
    })
    links.push({
      label: 'Historial Académico',
      icon: 'i-lucide-book-open',
      to: '/student/kardex',
      onSelect: () => { open.value = false }
    })
    return links
  }

  // Guardian has different home
  if (auth.isGuardian) {
    links.push({
      label: 'Mis Hijos',
      icon: 'i-lucide-users',
      to: '/guardian',
      onSelect: () => { open.value = false }
    })
    links.push({
      label: 'Tareas',
      icon: 'i-lucide-clipboard-list',
      to: '/guardian/tasks',
      onSelect: () => { open.value = false }
    })
    return links
  }

  // Staff home
  links.push({
    label: 'Inicio',
    icon: 'i-lucide-layout-dashboard',
    to: '/dashboard',
    onSelect: () => { open.value = false }
  })

  // Admin & Coordinator links
  if (auth.isAdmin || auth.isCoordinator) {
    links.push({
      label: 'Académico',
      icon: 'i-lucide-graduation-cap',
      type: 'trigger',
      defaultOpen: true,
      children: [{
        label: 'Años Académicos',
        icon: 'i-lucide-chevron-right',
        to: '/academic/years',
        onSelect: () => { open.value = false }
      }, {
        label: t.value.grades,
        icon: 'i-lucide-chevron-right',
        to: '/academic/grades',
        onSelect: () => { open.value = false }
      }, {
        label: t.value.groups,
        icon: 'i-lucide-chevron-right',
        to: '/academic/groups',
        onSelect: () => { open.value = false }
      }, {
        label: `Áreas y ${t.value.subjects}`,
        icon: 'i-lucide-chevron-right',
        to: '/academic/subjects',
        onSelect: () => { open.value = false }
      }, {
        label: 'Horarios',
        icon: 'i-lucide-clock',
        to: '/academic/schedules',
        onSelect: () => { open.value = false }
      }]
    })
  }

  // Staff links (admin, coordinator, teacher)
  if (auth.isStaff) {
    if (auth.isAdmin || auth.isCoordinator) {
      links.push({
        label: 'Estudiantes',
        icon: 'i-lucide-users',
        type: 'trigger',
        children: [{
          label: 'Listado',
          icon: 'i-lucide-chevron-right',
          to: '/students',
          onSelect: () => { open.value = false }
        }, ...(
          t.value.showSimat
            ? [{
                label: t.value.simatLabel,
                icon: 'i-lucide-chevron-right',
                to: '/students/simat-export',
                onSelect: () => { open.value = false }
              }]
            : []
        )]
      })
    } else {
      links.push({
        label: 'Estudiantes',
        icon: 'i-lucide-users',
        to: '/students',
        onSelect: () => { open.value = false }
      })
    }

    if (auth.isAdmin || auth.isCoordinator) {
      links.push({
        label: 'Docentes',
        icon: 'i-lucide-briefcase',
        to: '/teachers',
        onSelect: () => { open.value = false }
      })
    }

    if ((auth.isAdmin || auth.isCoordinator) && institution.isHigherEd) {
      links.push({
        label: 'Matrículas',
        icon: 'i-lucide-book-open-check',
        to: '/enrollments',
        onSelect: () => { open.value = false }
      })
    }

    links.push({
      label: 'Notas',
      icon: 'i-lucide-file-text',
      type: 'trigger',
      children: [{
        label: 'Registrar Notas',
        icon: 'i-lucide-chevron-right',
        to: '/grades/record',
        onSelect: () => { open.value = false }
      }, {
        label: 'Planilla',
        icon: 'i-lucide-chevron-right',
        to: '/grades/worksheet',
        onSelect: () => { open.value = false }
      }]
    })

    if (auth.isTeacher && !auth.isAdmin && !auth.isCoordinator) {
      links.push({
        label: 'Mi Horario',
        icon: 'i-lucide-clock',
        to: '/academic/schedules/teacher',
        onSelect: () => { open.value = false }
      })
    }

    links.push({
      label: 'Asistencia',
      icon: 'i-lucide-calendar-check',
      to: '/attendance',
      onSelect: () => { open.value = false }
    })

    links.push({
      label: 'Tareas',
      icon: 'i-lucide-clipboard-list',
      to: '/tasks',
      onSelect: () => { open.value = false }
    })

    links.push({
      label: 'Reportes',
      icon: 'i-lucide-bar-chart-3',
      type: 'trigger',
      children: [{
        label: t.value.reportCards,
        icon: 'i-lucide-chevron-right',
        to: institution.isHigherEd ? '/reports/kardex' : '/reports/cards',
        onSelect: () => { open.value = false }
      }, {
        label: t.value.certificates,
        icon: 'i-lucide-chevron-right',
        to: '/reports/certificates',
        onSelect: () => { open.value = false }
      }, {
        label: 'Consolidado',
        icon: 'i-lucide-chevron-right',
        to: '/reports/consolidation',
        onSelect: () => { open.value = false }
      }, {
        label: 'Estudiantes en Riesgo',
        icon: 'i-lucide-chevron-right',
        to: '/reports/failing',
        onSelect: () => { open.value = false }
      }, {
        label: 'Índice de Riesgo IA',
        icon: 'i-lucide-chevron-right',
        to: '/reports/risk',
        onSelect: () => { open.value = false }
      }]
    })

    if ((auth.isAdmin || auth.isCoordinator) && t.value.showConvivencia) {
      links.push({
        label: 'Convivencia',
        icon: 'i-lucide-shield',
        to: '/convivencia',
        onSelect: () => { open.value = false }
      })
    }

    if (t.value.showSiee) links.push({
      label: 'SIEE',
      icon: 'i-lucide-target',
      type: 'trigger',
      children: [{
        label: 'Logros',
        icon: 'i-lucide-chevron-right',
        to: '/siee/achievements',
        onSelect: () => { open.value = false }
      }, {
        label: 'Registrar Logros',
        icon: 'i-lucide-chevron-right',
        to: '/siee/achievements/record',
        onSelect: () => { open.value = false }
      }, {
        label: 'Nivelaciones',
        icon: 'i-lucide-chevron-right',
        to: '/siee/remedials',
        onSelect: () => { open.value = false }
      }]
    })
  }

  return links
})

const settingsLinks = computed<NavigationMenuItem[]>(() => {
  const links: NavigationMenuItem[] = []

  if (auth.isAdmin) {
    links.push({
      label: 'Institución',
      icon: 'i-lucide-building-2',
      to: '/institution/settings',
      onSelect: () => { open.value = false }
    })
  }

  links.push({
    label: 'Mi Perfil',
    icon: 'i-lucide-user',
    to: '/settings',
    onSelect: () => { open.value = false }
  })

  return links
})
</script>

<template>
  <UDashboardGroup unit="rem">
    <UDashboardSidebar
      id="default"
      v-model:open="open"
      collapsible
      resizable
      class="bg-elevated/25"
      :ui="{ footer: 'lg:border-t lg:border-default' }"
    >
      <template #header="{ collapsed }">
        <NuxtLink to="/dashboard" class="flex items-center px-2 py-2" :class="{ 'justify-center': collapsed }">
          <span v-if="collapsed" class="text-xl font-bold text-primary">A</span>
          <span v-else class="text-xl font-bold">Aula<span class="text-primary">360</span></span>
        </NuxtLink>
      </template>

      <template #default="{ collapsed }">
        <UDashboardSearchButton :collapsed="collapsed" class="bg-transparent ring-default" />

        <UNavigationMenu
          :collapsed="collapsed"
          :items="mainLinks"
          orientation="vertical"
          tooltip
          popover
        />

        <UNavigationMenu
          :collapsed="collapsed"
          :items="settingsLinks"
          orientation="vertical"
          tooltip
          class="mt-auto"
        />
      </template>

      <template #footer="{ collapsed }">
        <UserMenu :collapsed="collapsed" />
      </template>
    </UDashboardSidebar>

    <slot />

    <NotificationsSlideover />
  </UDashboardGroup>
</template>
