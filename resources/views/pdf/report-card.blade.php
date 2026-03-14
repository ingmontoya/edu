<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletin - {{ $student->user->name }}</title>
    <style>
        @page {
            margin: 14mm 18mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #333;
        }

        .page {
            padding: 0;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header .year {
            font-size: 11px;
            margin-bottom: 8px;
        }

        .header .report-title {
            font-size: 13px;
            font-weight: bold;
        }

        /* Student Info */
        .student-info {
            margin-bottom: 15px;
        }

        .student-info table {
            width: 100%;
        }

        .student-info td {
            padding: 2px 5px;
            font-size: 10px;
        }

        .student-info .label {
            font-weight: normal;
        }

        .student-info .value {
            font-weight: bold;
        }

        /* Grades Table */
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .grades-table th,
        .grades-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            vertical-align: top;
        }

        .grades-table th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }

        .grades-table .col-area {
            width: 70%;
        }

        .grades-table .col-ih {
            width: 8%;
            text-align: center;
        }

        .grades-table .col-grade {
            width: 22%;
            text-align: center;
        }

        /* Area Row */
        .area-row td {
            background: #e8e8e8;
            font-weight: bold;
            font-size: 10px;
        }

        .area-row .area-grade {
            text-align: right;
            padding-right: 10px;
        }

        /* Subject Row */
        .subject-row td {
            padding: 3px 6px;
        }

        .subject-header {
            margin-bottom: 2px;
        }

        .subject-name {
            font-weight: bold;
        }

        .subject-teacher {
            font-size: 8px;
            color: #555;
            font-weight: normal;
        }

        .subject-topic {
            font-size: 8px;
            margin-bottom: 2px;
        }

        .subject-topic .label {
            font-weight: bold;
        }

        .subject-performance {
            font-size: 8px;
        }

        .subject-performance .label {
            font-weight: bold;
        }

        /* Grade colors */
        .grade-superior { color: #006400; }
        .grade-alto { color: #0000cd; }
        .grade-basico { color: #8b4513; }
        .grade-bajo { color: #cc0000; }

        /* Observations */
        .observations {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #000;
        }

        .observations .title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .observations .content {
            min-height: 40px;
        }

        /* Signature */
        .signature {
            margin-top: 40px;
            text-align: center;
        }

        .signature-line {
            display: inline-block;
            border-top: 1px solid #000;
            width: 250px;
            padding-top: 5px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10px;
        }

        .signature-role {
            font-size: 9px;
            color: #555;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 3px;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>{{ strtoupper($institution->name) }}</h1>
            <div class="year">Ano: {{ $period->academicYear->year }}</div>
            <div class="report-title">BOLETIN DE {{ strtoupper($period->name) }}</div>
        </div>

        <!-- Student Info -->
        <div class="student-info">
            <table>
                <tr>
                    <td class="label">Estudiante</td>
                    <td class="value">: {{ $student->user->name }}</td>
                    <td class="label">Codigo</td>
                    <td class="value">: {{ $student->enrollment_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Curso</td>
                    <td class="value">: {{ $group->full_name ?? ($grade->name . ' ' . $group->name) }}</td>
                    <td class="label">Num. de Asignaturas</td>
                    <td class="value">: {{ $totalSubjects }}</td>
                </tr>
            </table>
        </div>

        <!-- Grades Table -->
        <table class="grades-table">
            <thead>
                <tr>
                    <th class="col-area">AREA / ASIGNATURAS</th>
                    <th class="col-ih">I.H</th>
                    <th class="col-grade">VALORACION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gradesByArea as $areaName => $records)
                    @php
                        $areaAvg = $records->avg('grade');
                        $areaLevel = match(true) {
                            $areaAvg >= 4.6 => 'SUPERIOR',
                            $areaAvg >= 4.0 => 'ALTO',
                            $areaAvg >= 3.0 => 'BASICO',
                            default => 'BAJO',
                        };
                        $areaClass = match(true) {
                            $areaAvg >= 4.6 => 'grade-superior',
                            $areaAvg >= 4.0 => 'grade-alto',
                            $areaAvg >= 3.0 => 'grade-basico',
                            default => 'grade-bajo',
                        };
                        $areaTotalHours = $records->sum('subject.weekly_hours');
                    @endphp
                    <!-- Area Header -->
                    <tr class="area-row">
                        <td>{{ strtoupper($areaName) }}</td>
                        <td class="col-ih"></td>
                        <td class="col-grade area-grade {{ $areaClass }}">{{ number_format($areaAvg * 20, 0) }} - {{ $areaLevel }}</td>
                    </tr>

                    @foreach($records as $record)
                        @php
                            $subjectLevel = match(true) {
                                $record->grade >= 4.6 => 'SUPERIOR',
                                $record->grade >= 4.0 => 'ALTO',
                                $record->grade >= 3.0 => 'BASICO',
                                default => 'BAJO',
                            };
                            $subjectClass = match(true) {
                                $record->grade >= 4.6 => 'grade-superior',
                                $record->grade >= 4.0 => 'grade-alto',
                                $record->grade >= 3.0 => 'grade-basico',
                                default => 'grade-bajo',
                            };
                            $teacher = $record->subject->teacherAssignments->first()?->teacher?->user?->name ?? 'Sin asignar';
                            $subjectAchievements = $achievements[$record->subject_id] ?? collect();
                        @endphp
                        <!-- Subject Row -->
                        <tr class="subject-row">
                            <td>
                                <div class="subject-header" style="padding-left: 10px;">
                                    <span class="subject-name">{{ strtoupper($record->subject->name) }}</span>
                                    <span class="subject-teacher"> - {{ strtoupper($teacher) }}</span>
                                </div>
                                @if($subjectAchievements->isNotEmpty())
                                    <div class="subject-topic" style="padding-left: 10px;">
                                        <span class="label">Tema:</span> {{ $subjectAchievements->first()->description }}
                                    </div>
                                @endif
                                @if($record->observations)
                                    <div class="subject-performance" style="padding-left: 10px;">
                                        <span class="label">Desempeno:</span> {{ $record->observations }}
                                    </div>
                                @endif
                            </td>
                            <td class="col-ih">{{ $record->subject->weekly_hours ?? '-' }}</td>
                            <td class="col-grade {{ $subjectClass }}">{{ number_format($record->grade * 20, 0) }} - {{ $subjectLevel }}</td>
                        </tr>
                    @endforeach
                @endforeach

                <!-- Standalone subjects (without area) -->
                @if(isset($standaloneSubjects) && $standaloneSubjects->count() > 0)
                    @foreach($standaloneSubjects as $record)
                        @php
                            $subjectLevel = match(true) {
                                $record->grade >= 4.6 => 'SUPERIOR',
                                $record->grade >= 4.0 => 'ALTO',
                                $record->grade >= 3.0 => 'BASICO',
                                default => 'BAJO',
                            };
                            $subjectClass = match(true) {
                                $record->grade >= 4.6 => 'grade-superior',
                                $record->grade >= 4.0 => 'grade-alto',
                                $record->grade >= 3.0 => 'grade-basico',
                                default => 'grade-bajo',
                            };
                            $teacher = $record->subject->teacherAssignments->first()?->teacher?->user?->name ?? 'Sin asignar';
                            $subjectAchievements = $achievements[$record->subject_id] ?? collect();
                        @endphp
                        <tr class="subject-row">
                            <td>
                                <div class="subject-header">
                                    <span class="subject-name">{{ strtoupper($record->subject->name) }}</span>
                                    <span class="subject-teacher"> - {{ strtoupper($teacher) }}</span>
                                </div>
                                @if($subjectAchievements->isNotEmpty())
                                    <div class="subject-topic">
                                        <span class="label">Tema:</span> {{ $subjectAchievements->first()->description }}
                                    </div>
                                @endif
                                @if($record->observations)
                                    <div class="subject-performance">
                                        <span class="label">Desempeno:</span> {{ $record->observations }}
                                    </div>
                                @endif
                            </td>
                            <td class="col-ih">{{ $record->subject->weekly_hours ?? '-' }}</td>
                            <td class="col-grade {{ $subjectClass }}">{{ number_format($record->grade * 20, 0) }} - {{ $subjectLevel }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- Observations / Recovery -->
        @if($failingSubjects && $failingSubjects->count() > 0)
            <div class="observations">
                <div class="title">OBSERVACIONES:</div>
                <div class="content">
                    Fechas para presentar examenes de recuperacion:
                    @foreach($failingSubjects as $fs)
                        {{ strtoupper($fs->subject->name) }} :
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Signature -->
        <div class="signature">
            <div class="signature-line">
                <div class="signature-name">{{ strtoupper($director?->user?->name ?? '') }}</div>
                <div class="signature-role">DIRECTOR DE CURSO</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Generado el {{ $generatedAt }} | {{ $institution->name }}
        </div>
    </div>
</body>
</html>
