import type { Attendance, AttendanceInput, AttendanceSummary } from '~/types/school'

interface AttendanceRecordResponse {
  student_id: number
  student_name: string
  document_number: string
  attendance_id: number | null
  status: string | null
  observation: string | null
}

interface AttendanceReportResponse {
  summary: AttendanceSummary
  records: Attendance[]
}

interface DailyAttendanceResponse {
  date: string
  group: any
  students: Array<{
    id: number
    name: string
    status: string | null
  }>
  summary: {
    total: number
    registered: number
    pending: number
  }
}

export const useAttendance = () => {
  const api = useApi()

  // Get attendance for a group on a specific date
  const getAttendance = (groupId: number, date: string) => {
    return api.get<AttendanceRecordResponse[]>(`/attendance?group_id=${groupId}&date=${date}`)
  }

  // Bulk save attendance
  const saveAttendance = (groupId: number, periodId: number, date: string, records: AttendanceInput[]) => {
    return api.post<{ message: string, count: number }>('/attendance/bulk', {
      group_id: groupId,
      period_id: periodId,
      date,
      records
    })
  }

  // Update single attendance record
  const updateAttendance = (id: number, data: { status: string, observation?: string }) => {
    return api.put<Attendance>(`/attendance/${id}`, data)
  }

  // Get attendance report for a student
  const getAttendanceReport = (studentId: number, periodId: number) => {
    return api.get<AttendanceReportResponse>(`/attendance/report?student_id=${studentId}&period_id=${periodId}`)
  }

  // Get daily attendance for a group
  const getDailyAttendance = (groupId: number) => {
    return api.get<DailyAttendanceResponse>(`/attendance/daily/${groupId}`)
  }

  // Helper function to get status color
  const getStatusColor = (status: string | null): string => {
    switch (status) {
      case 'present': return 'green'
      case 'absent': return 'red'
      case 'late': return 'yellow'
      case 'excused': return 'blue'
      default: return 'gray'
    }
  }

  // Helper function to get status label
  const getStatusLabel = (status: string | null): string => {
    switch (status) {
      case 'present': return 'Presente'
      case 'absent': return 'Ausente'
      case 'late': return 'Tardanza'
      case 'excused': return 'Excusa'
      default: return '-'
    }
  }

  // Helper function to get status icon
  const getStatusIcon = (status: string | null): string => {
    switch (status) {
      case 'present': return 'i-lucide-check'
      case 'absent': return 'i-lucide-x'
      case 'late': return 'i-lucide-clock'
      case 'excused': return 'i-lucide-file-text'
      default: return 'i-lucide-minus'
    }
  }

  return {
    getAttendance,
    saveAttendance,
    updateAttendance,
    getAttendanceReport,
    getDailyAttendance,
    getStatusColor,
    getStatusLabel,
    getStatusIcon
  }
}
