<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Matrícula</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; line-height: 1.5; color: #1a1a1a; }
        .page { padding: 30px 40px; }
        .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-bottom: 3px; }
        .header .subtitle { font-size: 11px; }
        .header .dane { font-size: 10px; color: #555; }
        .doc-title { text-align: center; margin: 20px 0; }
        .doc-title h2 { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border: 2px solid #000; display: inline-block; padding: 6px 20px; }
        .folio { text-align: right; font-size: 9px; color: #777; margin-bottom: 20px; }
        .body-text { font-size: 11px; line-height: 2; text-align: justify; margin: 20px 0; }
        .body-text strong { font-weight: bold; }
        .signature-section { margin-top: 60px; text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 250px; margin: 0 auto 4px; }
        .signature-name { font-weight: bold; font-size: 11px; }
        .signature-title { font-size: 10px; }
        .footer { position: fixed; bottom: 20px; left: 40px; right: 40px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 6px; }
        .seal-note { margin-top: 8px; font-size: 10px; color: #555; font-style: italic; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>{{ $institution->name ?? 'INSTITUCIÓN EDUCATIVA' }}</h1>
            @if($institution->nit)
            <div class="subtitle">NIT: {{ $institution->nit }}</div>
            @endif
            @if($institution->dane_code)
            <div class="dane">Código DANE: {{ $institution->dane_code }}</div>
            @endif
            @if($institution->address)
            <div class="dane">{{ $institution->address }}@if($institution->city) — {{ $institution->city }}@endif</div>
            @endif
        </div>

        <div class="folio">No. {{ $folio }} | Expedida: {{ $generatedAt }}</div>

        <div class="doc-title">
            <h2>Constancia de Matrícula</h2>
        </div>

        <div class="body-text">
            <p>
                El/La suscrito/a Rector/a de la Institución Educativa
                <strong>{{ $institution->name ?? '' }}</strong>,
                @if($institution->city)ubicada en el municipio de <strong>{{ $institution->city }}</strong>,@endif
                hace constar que el/la estudiante:
            </p>
            <br>
            <p>
                <strong>Nombre completo:</strong> {{ strtoupper($student->user->name) }}<br>
                <strong>Tipo y número de documento:</strong> {{ $student->user->document_type ?? 'CC' }} {{ $student->user->document_number ?? '' }}<br>
                <strong>Grado:</strong> {{ $student->group->grade->name ?? '' }}<br>
                <strong>Grupo:</strong> {{ $student->group->name ?? '' }}<br>
                <strong>Año académico:</strong> {{ $academicYear->name }}<br>
                @if($student->enrollment_code)
                <strong>Código de matrícula:</strong> {{ $student->enrollment_code }}<br>
                @endif
            </p>
            <br>
            <p>
                Se encuentra <strong>debidamente matriculado/a</strong> en esta institución para el año académico
                <strong>{{ $academicYear->year }}</strong>, en calidad de estudiante activo/a.
            </p>
            <br>
            <p>
                La presente constancia se expide a solicitud del/de la interesado/a para los fines que estime convenientes.
            </p>
        </div>

        <div class="signature-section">
            <div class="seal-note">[Espacio para sello de la institución]</div>
            <br><br>
            <div class="signature-line"></div>
            <div class="signature-name">{{ $institution->rector_name ?? 'RECTOR/A' }}</div>
            <div class="signature-title">Rector/a</div>
            <div class="signature-title">{{ $institution->name ?? '' }}</div>
        </div>
    </div>

    <div class="footer">
        Documento expedido el {{ $generatedAt }} | {{ $institution->name ?? '' }}
        @if($institution->email) | {{ $institution->email }}@endif
    </div>
</body>
</html>
