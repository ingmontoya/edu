// @ts-check
import withNuxt from './.nuxt/eslint.config.mjs'

// Pre-existing lint debt — do not add new files here.
// Fix issues when you touch a file, then remove it from this list.
const LEGACY_IGNORE = [
  'app/composables/useAcademic.ts',
  'app/composables/useApi.ts',
  'app/composables/useAttendance.ts',
  'app/middleware/auth.ts',
  'app/pages/academic/grades/index.vue',
  'app/pages/academic/subjects/index.vue',
  'app/pages/academic/groups/index.vue',
  'app/pages/academic/periods/index.vue',
  'app/pages/academic/years/index.vue',
  'app/pages/attendance/index.vue',
  'app/pages/grades/worksheet.vue',
  'app/pages/guardian/index.vue',
  'app/pages/guardian/student/**',
  'app/pages/reports/cards.vue',
  'app/pages/reports/consolidation.vue',
  'app/pages/siee/achievements/index.vue',
  'app/pages/siee/achievements/record.vue',
  'app/pages/siee/remedials/**',
  'app/pages/siee/remedials/index.vue',
  'app/pages/siee/remedials/new.vue',
  'app/pages/students/**',
  'app/pages/students/index.vue',
  'app/pages/students/new.vue',
  'app/pages/teachers/assignments.vue',
  'app/pages/teachers/index.vue',
  'app/stores/academic.ts',
  'app/stores/auth.ts',
  'app/stores/institution.ts',
  'app/types/school.ts',
  'nuxt.config.ts'
]

export default withNuxt(
  { ignores: LEGACY_IGNORE },
  {
    rules: {
      'vue/no-multiple-template-root': 'off',
      'vue/max-attributes-per-line': ['error', { singleline: 3 }]
    }
  }
)
