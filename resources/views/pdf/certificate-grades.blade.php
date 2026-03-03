<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Notas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; line-height: 1.4; color: #1a1a1a; }
        .page { padding: 25px 35px; }
        .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { font-size: 13px; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .header .subtitle { font-size: 10px; }
        .header .dane { font-size: 9px; color: #555; }
        .doc-title { text-align: center; margin: 15px 0; }
        .doc-title h2 { font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border: 2px solid #000; display: inline-block; padding: 5px 16px; }
        .folio { text-align: right; font-size: 9px; color: #777; margin-bottom: 12px; }
        .student-info { background: #f5f5f5; padding: 10px 12px; margin-bottom: 15px; border: 1px solid #ddd; }
        .student-info p { margin: 2px 0; font-size: 10px; }
        .grades-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grades-table th { background: #1a1a1a; color: #fff; padding: 5px 8px; font-size: 10px; text-align: left; }
        .grades-table td { padding: 4px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        .grades-table tr:nth-child(even) td { background: #f9f9f9; }
        .period-header td { background: #e0e0e0; font-weight: bold; padding: 5px 8px; }
        .average-row td { background: #dbeafe; font-weight: bold; }
        .grade-value { font-weight: bold; }
        .grade-low { color: #dc2626; }
        .grade-basic { color: #d97706; }
        .grade-high { color: #2563eb; }
        .grade-superior { color: #16a34a; }
        .signature-section { margin-top: 40px; text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 220px; margin: 0 auto 4px; }
        .footer { position: fixed; bottom: 15px; left: 35px; right: 35px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>{{ $institution->name ?? 'INSTITUCIÓN EDUCATIVA' }}</h1>
            @if($institution->nit)<div class="subtitle">NIT: {{ $institution->nit }}</div>@endif
            @if($institution->dane_code)<div class="dane">Código DANE: {{ $institution->dane_code }}</div>@endif
        </div>

        <div class="folio">No. {{ $folio }} | Expedida: {{ $generatedAt }}</div>

        <div class="doc-title">
            <h2>Constancia de {{ $period ? 'Notas — ' . $period->name : 'Notas del Año' }}</h2>
        </div>

        <div class="student-info">
            <p><strong>Estudiante:</strong> {{ strtoupper($student->user->name) }}</p>
            <p><strong>Documento:</strong> {{ $student->user->document_type ?? 'CC' }} {{ $student->user->document_number ?? '' }}</p>
            <p><strong>Grado/Grupo:</strong> {{ $student->group->grade->name ?? '' }} — {{ $student->group->name ?? '' }}</p>
            <p><strong>Año académico:</strong> {{ $academicYear->name }}</p>
        </div>

        @if($byPeriod->isNotEmpty())
        <table class="grades-table">
            <thead>
                <tr>
                    <th>Asignatura</th>
                    <th style="width:60px; text-align:center;">Nota</th>
                    <th style="width:100px;">Desempeño</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byPeriod as $periodName => $records)
                @if(!$period)
                <tr class="period-header"><td colspan="3">{{ $periodName }}</td></tr>
                @endif
                @foreach($records as $record)
                @php
                    $gradeClass = '';
                    if ($record->grade !== null) {
                        if ($record->grade < 3.0) $gradeClass = 'grade-low';
                        elseif ($record->grade < 4.0) $gradeClass = 'grade-basic';
                        elseif ($record->grade < 4.6) $gradeClass = 'grade-high';
                        else $gradeClass = 'grade-superior';
                    }
                @endphp
                <tr>
                    <td>{{ $record->subject->name ?? 'Sin asignatura' }}</td>
                    <td style="text-align:center;" class="grade-value {{ $gradeClass }}">
                        {{ $record->grade !== null ? number_format($record->grade, 1) : 'S/N' }}
                    </td>
                    <td>{{ $record->performance_label ?? '—' }}</td>
                </tr>
                @endforeach
                @endforeach

                @if($overallAverage !== null)
                <tr class="average-row">
                    <td><strong>PROMEDIO GENERAL</strong></td>
                    <td style="text-align:center;"><strong>{{ number_format($overallAverage, 1) }}</strong></td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
        @else
        <p style="text-align:center; padding: 20px; color: #666;">No hay notas registradas para el período seleccionado.</p>
        @endif

        <div class="signature-section">
            <br>
            <div class="signature-line"></div>
            <div style="font-weight:bold;">{{ $institution->rector_name ?? 'RECTOR/A' }}</div>
            <div style="font-size:9px;">Rector/a — {{ $institution->name ?? '' }}</div>
        </div>
    </div>

    <div class="footer">
        Expedida el {{ $generatedAt }} | {{ $institution->name ?? '' }}
        @if($institution->email) | {{ $institution->email }}@endif
    </div>
</body>
</html>
