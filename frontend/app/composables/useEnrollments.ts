import type { Enrollment, EnrollmentStatus } from '~/types/school'

export interface EnrollmentParams {
  student_id?: number
  subject_id?: number
  academic_year_id?: number
  semester_number?: number
  status?: string
}

export interface BulkEnrollPayload {
  student_id: number
  academic_year_id: number
  semester_number: number
  subject_ids: number[]
}

export interface BulkEnrollResult {
  created: Enrollment[]
  created_count: number
  skipped_count: number
}

export interface CalculateFinalsResult {
  updated: number
  skipped: number
  message: string
}

export const useEnrollments = () => {
  const api = useApi()

  const getEnrollments = (params?: EnrollmentParams) =>
    api.get<{ data: Enrollment[] }>('/enrollments', { params }).then(r => r.data)

  const createEnrollment = (data: Partial<Enrollment>) =>
    api.post<Enrollment>('/enrollments', data)

  const updateEnrollment = (id: number, data: { status?: EnrollmentStatus, final_grade?: number }) =>
    api.put<Enrollment>(`/enrollments/${id}`, data)

  const deleteEnrollment = (id: number) =>
    api.delete(`/enrollments/${id}`)

  const bulkCreateEnrollment = (payload: BulkEnrollPayload) =>
    api.post<BulkEnrollResult>('/enrollments/bulk', payload)

  const calculateFinalGrades = (academicYearId: number, studentId?: number) =>
    api.post<CalculateFinalsResult>('/enrollments/calculate-finals', {
      academic_year_id: academicYearId,
      ...(studentId ? { student_id: studentId } : {})
    })

  return {
    getEnrollments,
    createEnrollment,
    updateEnrollment,
    deleteEnrollment,
    bulkCreateEnrollment,
    calculateFinalGrades
  }
}
