@extends('emails.layouts.base')

@section('title', 'Consulta sin partner asignado — ' . config('app.name'))

@section('preheader', 'Ingresó una consulta para una localidad sin partner asignado. Requiere reasignación manual.')

@section('badge', 'Requiere atención')

@section('content')

    {{-- Saludo --}}
    <p style="margin:0 0 6px; font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#FF7500; text-transform:uppercase; letter-spacing:0.8px;">
        Hola,
    </p>
    <h1 style="margin:0 0 16px; font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:900; color:#000000; line-height:1.2;">
        Equipo PPA RED
    </h1>
    <p style="margin:0 0 16px; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:400; color:#374151; line-height:1.6;">
        Ingresó una nueva consulta a través de la plataforma <strong style="color:#000000;">PPA RED</strong>
        para una localidad que <strong>no tiene ningún partner asignado</strong>. Nadie fue notificado
        automáticamente: deberás reasignar esta consulta a un partner manualmente desde el Dashboard.
    </p>

    {{-- Aviso destacado --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#FEF2F2; border:1px solid #FCA5A5; border-left:4px solid #DC2626; border-radius:0 8px 8px 0; padding:16px 20px;">
                <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:600; color:#991B1B; line-height:1.6;">
                    Localidad sin partner asignado. Esta consulta necesita reasignación manual.
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="border-top:2px solid #F2F2F2;"></td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:36px; font-size:1px; line-height:36px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Título sección --}}
    <p style="margin:0 0 14px; font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Datos del solicitante
    </p>

    {{-- Tabla de datos del usuario --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="border:1px solid #E5E7EB; border-radius:8px; overflow:hidden;">
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; width:35%; vertical-align:top;">
                Nombre
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:600; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $data['name'] ?? '—' }}
            </td>
        </tr>
        <tr class="data-row">
            <td class="data-label" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Email
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                <a href="mailto:{{ $data['email'] ?? '' }}" style="color:#FF7500; text-decoration:none;">{{ $data['email'] ?? '—' }}</a>
            </td>
        </tr>
        @if (!empty($data['phone']))
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Teléfono
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $data['phone'] }}
            </td>
        </tr>
        @endif
        @if ($formSubmission->locality)
        <tr class="data-row">
            <td class="data-label" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Localidad
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $formSubmission->locality->name }}
            </td>
        </tr>
        @endif
        @if ($formSubmission->zone)
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Zona
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $formSubmission->zone->name }}
            </td>
        </tr>
        @endif
        @if ($formSubmission->province)
        <tr class="data-row">
            <td class="data-label" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; vertical-align:top;">
                Provincia
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#111827; vertical-align:top;">
                {{ $formSubmission->province->name }}
            </td>
        </tr>
        @endif
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Mensaje recibido --}}
    @if (!empty($data['message']))
    <p style="margin:0 0 14px; font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Mensaje del solicitante
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#FFF7ED; border-left:4px solid #FF7500; border-radius:0 6px 6px 0; padding:20px 24px;">
                <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:400; color:#374151; line-height:1.7; white-space:pre-line;">{{ $data['message'] }}</p>
            </td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>
    @endif

    {{-- Fecha y referencia --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding:12px 16px; background-color:#F9FAFB; border:1px solid #E5E7EB; border-radius:6px;">
                <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#6B7280; line-height:1.6;">
                    <strong style="color:#374151;">Fecha de consulta:</strong>
                    {{ $formSubmission->created_at->format('d/m/Y \a \l\a\s H:i') }} hs
                    &nbsp;·&nbsp;
                    <strong style="color:#374151;">Ref. #{{ $formSubmission->id }}</strong>
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:56px; font-size:1px; line-height:56px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- CTA principal --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td align="center" style="border-radius:6px; background-color:#FF7500; padding:14px 40px;">
                <a href="{{ route('form_submissions.show', $formSubmission->id) }}" target="_blank"
                   class="btn-primary"
                   style="display:inline-block; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                    Reasignar consulta en el Dashboard
                </a>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:48px; font-size:1px; line-height:48px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Nota al pie del contenido --}}
    <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#9CA3AF; text-align:center; line-height:1.6;">
        El usuario ya recibió la confirmación de que su consulta fue registrada, pero no será atendida hasta que se le asigne un partner.
    </p>

@endsection
