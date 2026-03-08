import { defineStore } from 'pinia'
import type { Institution } from '~/types/school'

interface InstitutionState {
  institution: Institution | null
  loading: boolean
  error: string | null
}

export const useInstitutionStore = defineStore('institution', {
  state: (): InstitutionState => ({
    institution: null,
    loading: false,
    error: null
  }),

  getters: {
    isHigherEd: (state): boolean => state.institution?.education_level === 'higher'
  },

  actions: {
    async fetch() {
      this.loading = true
      this.error = null

      try {
        const { getInstitution } = useAcademic()
        this.institution = await getInstitution()
      } catch (error: any) {
        this.error = error.message || 'Error al cargar la institucion'
      } finally {
        this.loading = false
      }
    },

    async update(data: Partial<Institution>) {
      this.loading = true
      this.error = null

      try {
        const { updateInstitution } = useAcademic()
        this.institution = await updateInstitution(data)
        return { success: true }
      } catch (error: any) {
        this.error = error.message || 'Error al actualizar la institucion'
        return { success: false, message: this.error }
      } finally {
        this.loading = false
      }
    }
  }
})
