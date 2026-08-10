<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientDiagnosticRequest extends FormRequest
{
    /**
     * Tipos de diagnóstico aceptados. Es una lista cerrada a propósito: el
     * cuerpo lo arma el navegador, así que sin esto cualquiera con sesión
     * iniciada podría escribir etiquetas arbitrarias en los logs.
     */
    public const KINDS = [
        'inertia_malformed_page',
        'inertia_navigation_type_error',
    ];

    /**
     * Tope de los fragmentos del cuerpo. Alcanza para reconocer un JSON
     * cortado a la mitad sin volcar la respuesta entera al log.
     */
    public const EXCERPT_LIMIT = 1000;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kind' => ['required', 'string', Rule::in(self::KINDS)],
            'page_url' => ['required', 'string', 'url', 'max:2048'],
            'request_url' => ['nullable', 'string', 'url', 'max:2048'],
            'status' => ['required', 'integer', 'between:0,599'],
            'content_type' => ['nullable', 'string', 'max:255'],
            'content_length_header' => ['nullable', 'integer', 'min:0'],
            'received_length' => ['nullable', 'integer', 'min:0'],
            'body_head' => ['nullable', 'string', 'max:'.self::EXCERPT_LIMIT],
            'body_tail' => ['nullable', 'string', 'max:'.self::EXCERPT_LIMIT],
        ];
    }
}
