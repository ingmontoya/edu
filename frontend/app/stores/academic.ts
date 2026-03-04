import { defineStore } from 'pinia'
import type { AcademicYear, Period, Grade, Group, Area, Subject } from '~/types/school'

interface AcademicState {
  academicYears: AcademicYear[]
  activeYear: AcademicYear | null
  periods: Period[]
  activePeriod: Period | null
  grades: Grade[]
  groups: Group[]
  areas: Area[]
  subjects: Subject[]
  loading: boolean
  error: string | null
}

export const useAcademicStore = defineStore('academic', {
  state: (): AcademicState => ({
    academicYears: [],
    activeYear: null,
    periods: [],
    activePeriod: null,
    grades: [],
    groups: [],
    areas: [],
    subjects: [],
    loading: false,
    error: null
  }),

  getters: {
    openPeriods: state => state.periods.filter(p => !p.is_closed),
    currentPeriod: (state) => {
      const today = new Date()
      return state.periods.find((p) => {
        const start = new Date(p.start_date)
        const end = new Date(p.end_date)
        return today >= start && today <= end
      }) || state.periods[0]
    },
    gradesByLevel: (state) => {
      const levels = ['preescolar', 'primaria', 'secundaria', 'media']
      return levels.reduce((acc, level) => {
        acc[level] = state.grades.filter(g => g.level === level)
        return acc
      }, {} as Record<string, Grade[]>)
    }
  },

  actions: {
    async fetchAcademicYears() {
      this.loading = true
      try {
        const { getAcademicYears } = useAcademic()
        const response = await getAcademicYears({ per_page: 100 })
        this.academicYears = response.data
        this.activeYear = this.academicYears.find(y => y.is_active) || null
      } catch (error: any) {
        this.error = error.message
      } finally {
        this.loading = false
      }
    },

    async fetchPeriods(academicYearId?: number) {
      this.loading = true
      try {
        const { getPeriods } = useAcademic()
        const yearId = academicYearId || this.activeYear?.id
        const response = await getPeriods({ academic_year_id: yearId, per_page: 100 })
        this.periods = response.data

        // Set active period (current or first open)
        const today = new Date()
        this.activePeriod = this.periods.find((p) => {
          const start = new Date(p.start_date)
          const end = new Date(p.end_date)
          return today >= start && today <= end && !p.is_closed
        }) || this.periods.find(p => !p.is_closed) || this.periods[0] || null
      } catch (error: any) {
        this.error = error.message
      } finally {
        this.loading = false
      }
    },

    async fetchGrades() {
      this.loading = true
      try {
        const { getGrades } = useAcademic()
        const response = await getGrades({ per_page: 100 })
        this.grades = response.data
      } catch (error: any) {
        this.error = error.message
      } finally {
        this.loading = false
      }
    },

    async fetchGroups(params?: { grade_id?: number, academic_year_id?: number }) {
      this.loading = true
      try {
        const { getGroups } = useAcademic()
        const queryParams = {
          ...params,
          academic_year_id: params?.academic_year_id || this.activeYear?.id,
          per_page: 100
        }
        const response = await getGroups(queryParams)
        this.groups = response.data
      } catch (error: any) {
        this.error = error.message
      } finally {
        this.loading = false
      }
    },

    async fetchAreas() {
      this.loading = true
      try {
        const { getAreas } = useAcademic()
        this.areas = await getAreas()
      } catch (error: any) {
        this.error = error.message
      } finally {
        this.loading = false
      }
    },

    async fetchSubjects(params?: { area_id?: number, grade_id?: number }) {
      this.loading = true
      try {
        const { getSubjects } = useAcademic()
        const response = await getSubjects({ ...params, per_page: 200 })
        this.subjects = response.data
      } catch (error: any) {
        this.error = error.message
      } finally {
        this.loading = false
      }
    },

    async fetchAll() {
      await Promise.all([
        this.fetchAcademicYears(),
        this.fetchGrades(),
        this.fetchAreas()
      ])

      if (this.activeYear) {
        await Promise.all([
          this.fetchPeriods(this.activeYear.id),
          this.fetchGroups({ academic_year_id: this.activeYear.id })
        ])
      }
    },

    setActiveYear(year: AcademicYear) {
      this.activeYear = year
      this.fetchPeriods(year.id)
      this.fetchGroups({ academic_year_id: year.id })
    },

    setActivePeriod(period: Period) {
      this.activePeriod = period
    }
  }
})
