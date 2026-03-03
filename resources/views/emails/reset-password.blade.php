<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña - Aula360</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; margin: 0; padding: 20px; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #2563eb; padding: 32px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .header span { color: #93c5fd; }
        .body { padding: 32px; }
        .body p { color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .btn { display: block; width: fit-content; margin: 24px auto; background: #2563eb; color: #fff; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: bold; font-size: 15px; }
        .note { font-size: 13px; color: #9ca3af; margin-top: 24px; }
        .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Aula<span>360</span></h1>
        </div>
        <div class="body">
            <p>Hola,</p>
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>Aula360</strong>.</p>
            <p>Haz clic en el botón a continuación para crear una nueva contraseña:</p>

            <a href="{{ $resetUrl }}" class="btn">Restablecer contraseña</a>

            <p>Este enlace expirará en <strong>60 minutos</strong>.</p>

            <p class="note">
                Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña actual permanecerá sin cambios.
            </p>

            <p class="note">
                Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Aula360 — Sistema de Gestión Académica
        </div>
    </div>
</body>
</html>
