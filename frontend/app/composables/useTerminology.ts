/**
 * useTerminology
 * Returns UI labels adapted to the institution's education level.
 * k12    → Colegio/Escuela terminology (default)
 * higher → Educación superior terminology (Colegio Mayor, universidades, etc.)
 */
export function useTerminology() {
  const institution = useInstitutionStore()

  const t = computed(() => {
    const isHigher = institution.isHigherEd

    return {
      // Structure
      grade: isHigher ? 'Programa' : 'Grado',
      grades: isHigher ? 'Programas' : 'Grados',
      group: isHigher ? 'Semestre' : 'Grupo',
      groups: isHigher ? 'Semestres' : 'Grupos',
      period: isHigher ? 'Semestre académico' : 'Período',
      periods: isHigher ? 'Semestres académicos' : 'Períodos',
      subject: isHigher ? 'Materia' : 'Asignatura',
      subjects: isHigher ? 'Materias' : 'Asignaturas',

      // People
      rector: isHigher ? 'Rector' : 'Rector',
      guardian: isHigher ? 'Estudiante' : 'Acudiente',

      // Reports
      reportCard: isHigher ? 'Historial Académico' : 'Boletín',
      reportCards: isHigher ? 'Historial Académico' : 'Boletines',
      certificate: isHigher ? 'Certificado' : 'Constancia',
      certificates: isHigher ? 'Certificados' : 'Constancias',

      // Regulatory
      simatLabel: 'Exportar SIMAT',
      showSimat: !isHigher,
      showSiee: !isHigher,
      showConvivencia: !isHigher
    }
  })

  return { t }
}
