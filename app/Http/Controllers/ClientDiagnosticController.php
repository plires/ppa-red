<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientDiagnosticRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientDiagnosticController extends Controller
{
    /**
     * Registra una respuesta que rompió la navegación de Inertia en el cliente.
     *
     * El TypeError de Inertia en /dashboard/form_submissions ocurre al azar y
     * sólo en producción: el usuario ve una recarga y nadie se entera. Este
     * endpoint deja en el log del servidor la respuesta concreta que falló,
     * que es el único dato que falta para cerrar el diagnóstico.
     */
    public function store(ClientDiagnosticRequest $request): Response
    {
        $data = $request->validated();

        $contentLength = $data['content_length_header'] ?? null;
        $receivedLength = $data['received_length'] ?? null;

        // La hipótesis principal es una respuesta cortada: Laravel alcanza a
        // mandar los headers (incluido x-inertia, por eso Inertia la trata como
        // válida) y el cuerpo llega incompleto, así que el JSON no parsea y la
        // página queda sin `url`. Comparar lo declarado contra lo recibido es
        // lo que confirma o descarta esa hipótesis.
        $looksTruncated = ($contentLength === null || $receivedLength === null)
            ? null
            : $receivedLength < $contentLength;

        Log::warning("Diagnóstico del cliente: {$data['kind']}", [
            'user_id' => Auth::id(),
            'page_url' => $data['page_url'],
            'request_url' => $data['request_url'] ?? null,
            'status' => $data['status'],
            'content_type' => $data['content_type'] ?? null,
            'content_length_header' => $contentLength,
            'received_length' => $receivedLength,
            'looks_truncated' => $looksTruncated,
            'body_head' => $data['body_head'] ?? null,
            'body_tail' => $data['body_tail'] ?? null,
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        return response()->noContent();
    }
}
