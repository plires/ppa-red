<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>

<body>
    <h2>{{ $subject }}</h2>
    <h2>{{ $body }}</h2>
    <p><strong>Nombre del partner:</strong> {{ $partner->name }}</p>
    <p><strong>Datos para responder al usuario:</strong></p>
    <p><strong>Nombre: </strong>{{ $dataUser['name'] ?? 'Usuario anónimo' }}</p>
    <p><strong>Email: </strong>{{ $dataUser['email'] ?? '' }}</p>
    <p><strong>Teléfono: </strong>{{ $dataUser['phone'] ?? '' }}</p>
    <p>Fecha de envio de consulta: {{ $formSubmission->created_at->format('d/m/Y H:i') }}</p>
    <p>ID del FormSubmission: {{ $formSubmission->id }}</p>
    <p>responses: {{ $responses }}</p>
</body>

</html>
