<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje</title>
</head>

<body>
    <h2>Mensaje para el partner</h2>
    <p><strong>Mensaje:</strong> {{ $formResponse->message }}</p>
    <p><strong>Enviado por:</strong> {{ $formResponse->user->name ?? 'Usuario anónimo' }}</p>
    <p>Fecha: {{ $formResponse->created_at->format('d/m/Y H:i') }}</p>
</body>

</html>
