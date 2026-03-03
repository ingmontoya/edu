import type { GradeRecord, GradeRecordInput, WorksheetStudent, Subject } from '~/types/school'

interface GradeRecordResponse {
  student_id: number
  student_name: string
  document_number: string
  record_id: number | null
  grade: number | null
  performance_level: string | null
  performance_label: string | null
  observations: string | null
  recommendations: string | null
}

interface WorksheetResponse {
  group: any
  period: any
  subjects: Subject[]
  worksheet: WorksheetStudent[]
}

export const useGrades = () => {
  const api = useApi()

  // Get grade records for a group/subject/period
  const getGradeRecords = (groupId: number, subjectId: number, periodId: number) => {
    return api.get<GradeRecordResponse[]>(
      `/grade-records?group_id=${groupId}&subject_id=${subjectId}&period_id=${periodId}`
    )
  }

  // Bulk save grade records
  const saveGradeRecords = (subjectId: number, periodId: number, records: GradeRecordInput[]) => {
    return api.post<{ message: string; count: number }>('/grade-records/bulk', {
      subject_id: subjectId,
      period_id: periodId,
      records
    })
  }

  // Update single grade record
  const updateGradeRecord = (id: number, data: Partial<GradeRecord>) => {
    return api.put<GradeRecord>(`/grade-records/${id}`, data)
  }

  // Get worksheet (all grades for a group/period)
  const getWorksheet = (groupId: number, periodId: number) => {
    return api.get<WorksheetResponse>(`/grade-records/worksheet?group_id=${groupId}&period_id=${periodId}`)
  }

  // Get grades by student
  const getGradesByStudent = (studentId: number, periodId?: number) => {
    const params = periodId ? `?period_id=${periodId}` : ''
    return api.get<Record<number, GradeRecord[]>>(`/grade-records/by-student/${studentId}${params}`)
  }

  // Helper function to get performance color
  const getPerformanceColor = (grade: number | null): 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'error' | 'neutral' => {
    if (grade === null) return 'neutral'
    if (grade >= 4.6) return 'success'
    if (grade >= 4.0) return 'info'
    if (grade >= 3.0) return 'warning'
    return 'error'
  }

  // Helper function to get performance label
  const getPerformanceLabel = (grade: number | null): string => {
    if (grade === null) return '-'
    if (grade >= 4.6) return 'Superior'
    if (grade >= 4.0) return 'Alto'
    if (grade >= 3.0) return 'Basico'
    return 'Bajo'
  }

  return {
    getGradeRecords,
    saveGradeRecords,
    updateGradeRecord,
    getWorksheet,
    getGradesByStudent,
    getPerformanceColor,
    getPerformanceLabel
  }
}
