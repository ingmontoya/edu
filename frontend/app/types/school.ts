// User types
export interface User {
  id: number
  name: string
  email: string
  role: 'admin' | 'coordinator' | 'teacher' | 'guardian'
  document_type?: string
  document_number?: string
  phone?: string
  address?: string
  birth_date?: string
  photo?: string
  is_active: boolean
}

// Institution
export interface Institution {
  id: number
  name: string
  nit?: string
  dane_code?: string
  logo?: string
  address?: string
  phone?: string
  email?: string
  city?: string
  department?: string
  rector_name?: string
  grading_scale?: GradingScale
}

export interface GradingScale {
  superior: { min: number, max: number }
  high: { min: number, max: number }
  basic: { min: number, max: number }
  low: { min: number, max: number }
}

// Academic Year
export interface AcademicYear {
  id: number
  institution_id: number
  name: string
  year: number
  start_date: string
  end_date: string
  is_active: boolean
  periods?: Period[]
}

// Period
export interface Period {
  id: number
  academic_year_id: number
  name: string
  number: number
  weight: number
  start_date: string
  end_date: string
  is_active: boolean
  is_closed: boolean
  academic_year?: AcademicYear
}

// Grade (School Grade Level)
export interface Grade {
  id: number
  institution_id: number
  name: string
  short_name: string
  order: number
  level: 'preescolar' | 'primaria' | 'secundaria' | 'media'
  subjects?: Subject[]
  groups?: Group[]
  groups_count?: number
}

// Group
export interface Group {
  id: number
  grade_id: number
  academic_year_id: number
  director_id?: number
  name: string
  capacity?: number
  full_name?: string
  grade?: Grade
  academic_year?: AcademicYear
  director?: Teacher
  students?: Student[]
}

// Area
export interface Area {
  id: number
  institution_id: number
  name: string
  order: number
  subjects?: Subject[]
}

// Subject
export interface Subject {
  id: number
  area_id: number
  grade_id: number
  name: string
  weekly_hours: number
  intensity_hours?: number
  order: number
  area?: Area
  grade?: Grade
}

// Teacher
export interface Teacher {
  id: number
  user_id: number
  institution_id: number
  specialization?: string
  specialty?: string
  contract_type?: 'full_time' | 'part_time' | 'contractor'
  user?: User
  assignments?: TeacherAssignment[]
}

// Teacher Assignment
export interface TeacherAssignment {
  id: number
  teacher_id: number
  subject_id: number
  group_id: number
  academic_year_id: number
  teacher?: Teacher
  subject?: Subject
  group?: Group
  academic_year?: AcademicYear
}

// Student
export interface Student {
  id: number
  user_id: number
  group_id: number
  enrollment_code?: string
  enrollment_date: string
  status: 'active' | 'inactive' | 'withdrawn' | 'graduated'
  // SIMAT fields
  simat_code?: string
  stratum?: number
  health_insurer?: string
  ethnicity?: string
  disability_type?: string
  municipality?: string
  birth_municipality?: string
  user?: User
  group?: Group
  guardians?: Guardian[]
}

// Guardian
export interface Guardian {
  id: number
  user_id: number
  relationship?: string
  occupation?: string
  user?: User
  students?: Student[]
}

// Grade Record
export interface GradeRecord {
  id: number
  student_id: number
  subject_id: number
  period_id: number
  teacher_id: number
  grade?: number
  observations?: string
  recommendations?: string
  performance_level?: 'superior' | 'high' | 'basic' | 'low'
  performance_label?: string
  student?: Student
  subject?: Subject
  period?: Period
  teacher?: Teacher
}

// Attendance
export interface Attendance {
  id: number
  student_id: number
  group_id: number
  period_id: number
  date: string
  status: 'present' | 'absent' | 'late' | 'excused'
  observation?: string
  registered_by: number
  student?: Student
  group?: Group
  period?: Period
}

// Announcement
export interface Announcement {
  id: number
  institution_id: number
  user_id: number
  title: string
  content: string
  is_published: boolean
  published_at?: string
  author?: User
}

// API Response types
export interface ApiResponse<T> {
  data: T
  message?: string
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

// Form types
export interface GradeRecordInput {
  student_id: number
  grade: number | null
  observations?: string
  recommendations?: string
}

export interface AttendanceInput {
  student_id: number
  status: 'present' | 'absent' | 'late' | 'excused'
  observation?: string
}

// Worksheet types
export interface WorksheetStudent {
  student_id: number
  student_name: string
  grades: WorksheetGrade[]
  average: number | null
  performance: string | null
  ranking: number | null
}

export interface WorksheetGrade {
  subject_id: number
  subject_name: string
  area_name: string
  grade: number | null
  performance: string | null
}

// Report types
export interface ConsolidationStudent {
  student_id: number
  student_name: string
  grades: Record<number, number | null>
  average: number | null
  performance: string | null
  failing_count: number
  ranking: number | null
}

export interface AttendanceSummary {
  total_days: number
  present: number
  absent: number
  late: number
  excused: number
  percentage: number
}

// ============ SIEE Types ============

// Achievement (Logro)
export interface Achievement {
  id: number
  subject_id: number
  period_id: number
  code?: string
  description: string
  type: 'cognitive' | 'procedural' | 'attitudinal'
  order: number
  is_active: boolean
  subject?: Subject
  period?: Period
  indicators?: AchievementIndicator[]
}

// Achievement Indicator
export interface AchievementIndicator {
  id: number
  achievement_id: number
  code?: string
  description: string
  order: number
}

// Student Achievement Record
export interface StudentAchievement {
  id: number
  student_id: number
  achievement_id: number
  status: 'pending' | 'in_progress' | 'achieved' | 'not_achieved'
  observations?: string
  evaluated_by?: number
  evaluated_at?: string
  student?: Student
  achievement?: Achievement
  evaluator?: User
}

// Remedial Activity (Nivelacion/Recuperacion)
export interface RemedialActivity {
  id: number
  subject_id: number
  period_id: number
  teacher_id: number
  title: string
  description: string
  instructions?: string
  type: 'recovery' | 'reinforcement' | 'leveling'
  assigned_date: string
  due_date: string
  max_grade: number
  is_active: boolean
  subject?: Subject
  period?: Period
  teacher?: Teacher
  student_remedials?: StudentRemedial[]
}

// Student Remedial Assignment
export interface StudentRemedial {
  id: number
  student_id: number
  remedial_activity_id: number
  status: 'pending' | 'submitted' | 'graded' | 'excused'
  grade?: number
  submission_notes?: string
  teacher_feedback?: string
  submitted_at?: string
  graded_at?: string
  graded_by?: number
  student?: Student
  remedial_activity?: RemedialActivity
}

// Promotion Record
export interface PromotionRecord {
  id: number
  student_id: number
  academic_year_id: number
  decision: 'promoted' | 'retained' | 'early_promoted' | 'conditional'
  observations?: string
  failed_subjects?: number[]
  failed_count: number
  final_average?: number
  performance_level?: string
  decided_by?: number
  decided_at?: string
  student?: Student
  academic_year?: AcademicYear
}

// Performance Level type
export type PerformanceLevel = 'superior' | 'high' | 'basic' | 'low'

export const PerformanceLevelLabels: Record<PerformanceLevel, string> = {
  superior: 'Desempeno Superior',
  high: 'Desempeno Alto',
  basic: 'Desempeno Basico',
  low: 'Desempeno Bajo'
}

export const PerformanceLevelColors: Record<PerformanceLevel, string> = {
  superior: 'success',
  high: 'info',
  basic: 'warning',
  low: 'error'
}

// Achievement Status Labels
export const AchievementStatusLabels: Record<string, string> = {
  pending: 'Pendiente',
  in_progress: 'En Progreso',
  achieved: 'Alcanzado',
  not_achieved: 'No Alcanzado'
}

// Remedial Status Labels
export const RemedialStatusLabels: Record<string, string> = {
  pending: 'Pendiente',
  submitted: 'Entregado',
  graded: 'Calificado',
  excused: 'Excusado'
}

// ============ SIMAT Types ============

export type DisciplinaryRecordType = 'type1' | 'type2' | 'type3'
export type DisciplinaryRecordStatus = 'open' | 'in_process' | 'resolved' | 'escalated'
export type DisciplinaryCategory = 'verbal' | 'physical' | 'psychological' | 'cyberbullying' | 'other'

export interface DisciplinaryRecord {
  id: number
  institution_id: number
  student_id: number
  reporter_id: number
  period_id?: number
  type: DisciplinaryRecordType
  category: DisciplinaryCategory
  description: string
  date: string
  location?: string
  witnesses?: string
  action_taken?: string
  status: DisciplinaryRecordStatus
  resolution?: string
  resolved_at?: string
  notify_guardian: boolean
  commitment?: string
  student?: Student
  reporter?: User
  period?: Period
}

export const DisciplinaryTypeLabels: Record<DisciplinaryRecordType, string> = {
  type1: 'Tipo 1 - Conflicto o desacuerdo',
  type2: 'Tipo 2 - Agresión o acoso escolar',
  type3: 'Tipo 3 - Vulneración de derechos'
}

export const DisciplinaryStatusLabels: Record<DisciplinaryRecordStatus, string> = {
  open: 'Abierto',
  in_process: 'En Proceso',
  resolved: 'Resuelto',
  escalated: 'Escalado'
}

export const DisciplinaryCategoryLabels: Record<DisciplinaryCategory, string> = {
  verbal: 'Verbal',
  physical: 'Físico',
  psychological: 'Psicológico',
  cyberbullying: 'Ciberbullying',
  other: 'Otro'
}

export const DisciplinaryStatusColors: Record<DisciplinaryRecordStatus, string> = {
  open: 'error',
  in_process: 'warning',
  resolved: 'success',
  escalated: 'neutral'
}

// ============ AI Analysis History ============

export interface AiAnalysisRecord {
  id: number
  period: { id: number; name: string }
  risk_level: 'high' | 'medium' | 'low'
  risk_score: number
  narrative: string
  recommendations: Array<{ subject: string; strategy: string; activity: string }>
  created_at: string
}

export interface AiAnalysisEvolution {
  score_delta: number
  level_changed: boolean
  previous_level: 'high' | 'medium' | 'low'
  current_level: 'high' | 'medium' | 'low'
}

export interface AiAnalysisHistoryResponse {
  analyses: AiAnalysisRecord[]
  evolution: AiAnalysisEvolution | null
}

// ============ Risk Score ============

export type RiskLevel = 'low' | 'medium' | 'high'

export interface RiskScore {
  student_id: number
  student: {
    id: number
    name: string
    group: string
  }
  score: number
  level: RiskLevel
  signals: {
    failing_subjects: number
    total_subjects: number
    attendance_pct: number
    disciplinary_incidents: number
    pending_remedials: number
    grade_trend: number | null
  }
}
