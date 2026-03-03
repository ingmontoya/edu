<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletines</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .page {
            padding: 15px;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #1a365d;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .header-logo img {
            max-width: 70px;
            max-height: 70px;
        }

        .header-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .header-info h1 {
            font-size: 14px;
            color: #1a365d;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .header-info p {
            font-size: 9px;
            color: #666;
        }

        .header-period {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
            text-align: right;
        }

        .period-badge {
            background: #1a365d;
            color: white;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
        }

        .student-info {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .student-info table {
            width: 100%;
        }

        .student-info td {
            padding: 3px 5px;
        }

        .student-info .label {
            font-weight: bold;
            color: #4a5568;
            width: 100px;
        }

        .student-info .value {
            color: #1a202c;
        }

        .grades-section {
            margin-bottom: 15px;
        }

        .section-title {
            background: #1a365d;
            color: white;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 0;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .grades-table th,
        .grades-table td {
            border: 1px solid #cbd5e0;
            padding: 5px 8px;
            text-align: left;
        }

        .grades-table th {
            background: #edf2f7;
            font-weight: bold;
            color: #2d3748;
            font-size: 9px;
            text-transform: uppercase;
        }

        .grades-table td {
            font-size: 10px;
        }

        .grades-table .grade-cell {
            text-align: center;
            font-weight: bold;
            width: 50px;
        }

        .grades-table .performance-cell {
            width: 120px;
            font-size: 9px;
        }

        .area-header {
            background: #e2e8f0;
            font-weight: bold;
            color: #2d3748;
        }

        .performance-superior { color: #276749; background: #c6f6d5; }
        .performance-alto { color: #2b6cb0; background: #bee3f8; }
        .performance-basico { color: #975a16; background: #fefcbf; }
        .performance-bajo { color: #c53030; background: #fed7d7; }

        .summary-section {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-box {
            display: table-cell;
            width: 25%;
            padding: 5px;
        }

        .summary-card {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #1a365d;
        }

        .summary-card .label {
            font-size: 8px;
            color: #718096;
            text-transform: uppercase;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #cbd5e0;
            padding: 5px 10px;
            text-align: center;
            font-size: 9px;
        }

        .attendance-table th {
            background: #edf2f7;
            font-weight: bold;
        }

        .signatures-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }

        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 0 15px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
            padding-top: 5px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 9px;
        }

        .signature-role {
            font-size: 8px;
            color: #666;
        }

        .footer {
            text-align: center;
            font-size: 8px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    @foreach($reports as $report)
    <div class="page">
        <!-- Encabezado -->
        <div class="header">
            <div class="header-logo">
                @if($report['institution']->logo)
                    <img src="{{ storage_path('app/public/' . $report['institution']->logo) }}" alt="Logo">
                @else
                    <div style="width:70px;height:70px;background:#e2e8f0;border-radius:4px;"></div>
                @endif
            </div>
            <div class="header-info">
                <h1>{{ $report['institution']->name }}</h1>
                <p>NIT: {{ $report['institution']->nit }} - DANE: {{ $report['institution']->dane_code }}</p>
                <p>{{ $report['institution']->address }} - {{ $report['institution']->city }}, {{ $report['institution']->department }}</p>
                <p style="margin-top:5px;font-weight:bold;font-size:11px;">INFORME ACADEMICO</p>
            </div>
            <div class="header-period">
                <div class="period-badge">
                    {{ $report['period']->name }}<br>
                    {{ $report['period']->academicYear->year }}
                </div>
            </div>
        </div>

        <!-- Informacion del estudiante -->
        <div class="student-info">
            <table>
                <tr>
                    <td class="label">Estudiante:</td>
                    <td class="value" colspan="3"><strong>{{ $report['student']->user->name }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Documento:</td>
                    <td class="value">{{ $report['student']->user->document_type }} {{ $report['student']->user->document_number }}</td>
                    <td class="label">Grado:</td>
                    <td class="value">{{ $report['grade']->name }} - {{ $report['group']->name }}</td>
                </tr>
            </table>
        </div>

        <!-- Resumen -->
        <div class="summary-section">
            <div class="summary-box">
                <div class="summary-card">
                    <div class="value">{{ $report['periodAverage'] ?? '-' }}</div>
                    <div class="label">Promedio</div>
                </div>
            </div>
            <div class="summary-box">
                <div class="summary-card">
                    <div class="value" style="font-size:12px;">{{ $report['performanceLevel'] ?? '-' }}</div>
                    <div class="label">Desempeno</div>
                </div>
            </div>
            <div class="summary-box">
                <div class="summary-card">
                    <div class="value">{{ $report['ranking'] }}/{{ $report['totalStudents'] }}</div>
                    <div class="label">Puesto</div>
                </div>
            </div>
            <div class="summary-box">
                <div class="summary-card">
                    <div class="value">{{ $report['attendance']['percentage'] }}%</div>
                    <div class="label">Asistencia</div>
                </div>
            </div>
        </div>

        <!-- Tabla de notas -->
        <div class="grades-section">
            <div class="section-title">VALORACION ACADEMICA</div>
            <table class="grades-table">
                <thead>
                    <tr>
                        <th style="width:50%;">Asignatura</th>
                        <th style="width:15%;">Nota</th>
                        <th style="width:35%;">Desempeno</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['gradesByArea'] as $areaName => $records)
                        <tr class="area-header">
                            <td colspan="3">{{ $areaName }}</td>
                        </tr>
                        @foreach($records as $record)
                            @php
                                $perfClass = match(true) {
                                    $record->grade >= 4.6 => 'performance-superior',
                                    $record->grade >= 4.0 => 'performance-alto',
                                    $record->grade >= 3.0 => 'performance-basico',
                                    default => 'performance-bajo',
                                };
                            @endphp
                            <tr>
                                <td style="padding-left:20px;">{{ $record->subject->name }}</td>
                                <td class="grade-cell {{ $perfClass }}">{{ number_format($record->grade, 1) }}</td>
                                <td class="performance-cell {{ $perfClass }}">{{ $record->performance_label }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Firmas -->
        <div class="signatures-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $report['director']?->user?->name ?? '____________________' }}</div>
                <div class="signature-role">Director(a) de Grupo</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">____________________</div>
                <div class="signature-role">Coordinador(a)</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">____________________</div>
                <div class="signature-role">Rector(a)</div>
            </div>
        </div>

        <div class="footer">
            Generado el {{ $report['generatedAt'] }} | {{ $report['institution']->name }}
        </div>
    </div>
    @endforeach
</body>
</html>
