import type { ConsolidationStudent, Subject, RiskScore, AiAnalysisHistoryResponse } from '~/types/school'

export interface AiRecommendation {
  subject: string
  strategy: string
  activity: string
}

export interface AiAnalysis {
  narrative: string
  recommendations: AiRecommendation[]
}

interface ConsolidationResponse {
  group: unknown
  period: unknown
  subjects: Subject[]
  consolidation: ConsolidationStudent[]
  summary: {
    total_students: number
    students_with_failing: number
    average_grade: number
  }
}

interface FailingStudent {
  student: {
    id: number
    name: string
    group: string
  }
  failing_subjects: Array<{
    subject: string
    area: string
    grade: number
  }>
  failing_count: number
}

interface FailingStudentsResponse {
  students: FailingStudent[]
  summary: {
    total_students_failing: number
    total_failing_records: number
  }
}

interface AttendanceSummaryStudent {
  student: {
    id: number
    name: string
    group: string
  }
  total_days: number
  present: number
  absent: number
  late: number
  excused: number
  percentage: number
}

interface RiskScoresResponse {
  students: RiskScore[]
  summary: {
    total: number
    high_risk: number
    medium_risk: number
    low_risk: number
    average_score: number
  }
}

interface AttendanceSummaryResponse {
  students: AttendanceSummaryStudent[]
  summary: {
    total_students: number
    average_attendance: number
    students_below_80: number
  }
}

export const useReports = () => {
  const api = useApi()

  // Get consolidation report
  const getConsolidation = (groupId: number, periodId: number) => {
    return api.get<ConsolidationResponse>(`/reports/consolidation?group_id=${groupId}&period_id=${periodId}`)
  }

  // Get failing students report
  const getFailingStudents = (periodId: number, groupId?: number) => {
    const params = groupId ? `&group_id=${groupId}` : ''
    return api.get<FailingStudentsResponse>(`/reports/failing-students?period_id=${periodId}${params}`)
  }

  // Get risk scores
  const getRiskScores = (periodId: number, groupId?: number) => {
    const params = groupId ? `&group_id=${groupId}` : ''
    return api.get<RiskScoresResponse>(`/reports/risk-scores?period_id=${periodId}${params}`)
  }

  // Get attendance summary report
  const getAttendanceSummary = (periodId: number, groupId?: number) => {
    const params = groupId ? `&group_id=${groupId}` : ''
    return api.get<AttendanceSummaryResponse>(`/reports/attendance-summary?period_id=${periodId}${params}`)
  }

  // Report cards
  const getReportCard = (studentId: number, periodId: number) => {
    return api.get(`/report-cards/student/${studentId}/period/${periodId}`)
  }

  const downloadReportCardPdf = async (studentId: number, periodId: number) => {
    const config = useRuntimeConfig()
    const auth = useAuthStore()

    const response = await fetch(
      `${config.public.apiUrl}/report-cards/student/${studentId}/period/${periodId}/pdf`,
      {
        headers: {
          Authorization: `Bearer ${auth.token}`
        }
      }
    )

    if (!response.ok) {
      const errorText = await response.text()
      console.error('Error downloading PDF:', errorText)
      throw new Error(`Error al descargar el boletín: ${response.status}`)
    }

    const blob = await response.blob()

    // Verify it's actually a PDF
    if (blob.type !== 'application/pdf' && !blob.type.includes('pdf')) {
      console.error('Response is not a PDF:', blob.type)
      throw new Error('La respuesta no es un PDF válido')
    }

    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `boletin_${studentId}_${periodId}.pdf`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
  }

  const downloadBulkReportCardsPdf = async (groupId: number, periodId: number) => {
    const config = useRuntimeConfig()
    const auth = useAuthStore()

    const response = await fetch(
      `${config.public.apiUrl}/report-cards/group/${groupId}/period/${periodId}/pdf`,
      {
        headers: {
          Authorization: `Bearer ${auth.token}`
        }
      }
    )

    if (!response.ok) {
      const errorText = await response.text()
      console.error('Error downloading bulk PDF:', errorText)
      throw new Error(`Error al descargar los boletines: ${response.status}`)
    }

    const blob = await response.blob()

    // Verify it's actually a PDF
    if (blob.type !== 'application/pdf' && !blob.type.includes('pdf')) {
      console.error('Response is not a PDF:', blob.type)
      throw new Error('La respuesta no es un PDF válido')
    }

    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `boletines_grupo_${groupId}_periodo_${periodId}.pdf`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
  }

  const downloadEnrollmentCertificate = async (studentId: number, academicYearId?: number) => {
    const config = useRuntimeConfig()
    const auth = useAuthStore()
    const qs = academicYearId ? `?academic_year_id=${academicYearId}` : ''

    const response = await fetch(
      `${config.public.apiUrl}/certificates/student/${studentId}/enrollment${qs}`,
      { headers: { Authorization: `Bearer ${auth.token}` } }
    )

    if (!response.ok) throw new Error(`Error al descargar constancia: ${response.status}`)

    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `constancia_matricula_${studentId}.pdf`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
  }

  const downloadGradesCertificate = async (studentId: number, options: { periodId?: number, academicYearId?: number } = {}) => {
    const config = useRuntimeConfig()
    const auth = useAuthStore()
    const params = new URLSearchParams()
    if (options.periodId) params.append('period_id', String(options.periodId))
    if (options.academicYearId) params.append('academic_year_id', String(options.academicYearId))
    const qs = params.toString()

    const response = await fetch(
      `${config.public.apiUrl}/certificates/student/${studentId}/grades${qs ? '?' + qs : ''}`,
      { headers: { Authorization: `Bearer ${auth.token}` } }
    )

    if (!response.ok) throw new Error(`Error al descargar constancia: ${response.status}`)

    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `constancia_notas_${studentId}.pdf`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
  }

  // AI Insights
  const getAiStudentAnalysis = (studentId: number, periodId: number) => {
    return api.post<AiAnalysis>('/reports/ai/student-analysis', { student_id: studentId, period_id: periodId })
  }

  const getAiWeeklySummary = (periodId: number, groupId?: number) => {
    const body: Record<string, number> = { period_id: periodId }
    if (groupId) body.group_id = groupId
    return api.post<{ summary: string }>('/reports/ai/weekly-summary', body)
  }

  const getStudentAiAnalyses = (studentId: number) => {
    return api.get<AiAnalysisHistoryResponse>(`/students/${studentId}/ai-analyses`)
  }

  return {
    getConsolidation,
    getFailingStudents,
    getRiskScores,
    getAttendanceSummary,
    getReportCard,
    downloadReportCardPdf,
    downloadBulkReportCardsPdf,
    downloadEnrollmentCertificate,
    downloadGradesCertificate,
    getAiStudentAnalysis,
    getAiWeeklySummary,
    getStudentAiAnalyses
  }
}
