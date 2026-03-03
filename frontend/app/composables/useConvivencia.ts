import type { DisciplinaryRecord, PaginatedResponse } from '~/types/school'

interface DisciplinaryFilters {
  student_id?: number
  type?: string
  status?: string
  period_id?: number
  date_from?: string
  date_to?: string
  per_page?: number
  page?: number
}

export const useConvivencia = () => {
  const api = useApi()

  const getRecords = (filters: DisciplinaryFilters = {}) => {
    const params = new URLSearchParams()
    Object.entries(filters).forEach(([k, v]) => {
      if (v !== undefined && v !== null && v !== '') params.append(k, String(v))
    })
    const qs = params.toString()
    return api.get<PaginatedResponse<DisciplinaryRecord>>(`/disciplinary${qs ? '?' + qs : ''}`)
  }

  const getRecord = (id: number) => {
    return api.get<{ record: DisciplinaryRecord, student_history: DisciplinaryRecord[] }>(`/disciplinary/${id}`)
  }

  const createRecord = (data: Partial<DisciplinaryRecord>) => {
    return api.post<DisciplinaryRecord>('/disciplinary', data)
  }

  const updateRecord = (id: number, data: Partial<DisciplinaryRecord>) => {
    return api.put<DisciplinaryRecord>(`/disciplinary/${id}`, data)
  }

  const deleteRecord = (id: number) => {
    return api.delete(`/disciplinary/${id}`)
  }

  const getStudentHistory = (studentId: number) => {
    return api.get<DisciplinaryRecord[]>(`/students/${studentId}/disciplinary`)
  }

  return {
    getRecords,
    getRecord,
    createRecord,
    updateRecord,
    deleteRecord,
    getStudentHistory
  }
}
