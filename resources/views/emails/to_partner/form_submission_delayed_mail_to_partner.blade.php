<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrasada la respuesta hace 48 Hs</title>
</head>

<body>
    <h2>Mensaje para el partner por retraso de rta hace 48 hs</h2>
    <p><strong>Mensaje para Partner:</strong> {{ $partner->name }}</p>
    <p><strong>Datos para responder al usuario:</strong></p>
    <p><strong>Nombre: </strong>{{ $dataUser['name'] ?? 'Usuario anónimo' }}</p>
    <p><strong>Email: </strong>{{ $dataUser['email'] ?? '' }}</p>
    <p><strong>Teléfono: </strong>{{ $dataUser['phone'] ?? '' }}</p>
    <p>Fecha de envio de consulta: {{ $formSubmission->created_at->format('d/m/Y H:i') }}</p>
</body>

</html>
