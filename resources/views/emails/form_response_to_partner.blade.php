@extends('emails.layouts.base')

@section('title', 'Nueva consulta recibida — ' . config('app.name'))

@section('preheader', 'Recibiste una nueva consulta en tu plataforma PPA RED.')

@section('badge', 'Nueva Consulta')

@section('content')

@php
    $submissionStatus = $formSubmission->status?->name ?? '—';
    if (str_contains($submissionStatus, 'Pendiente')) {
        [$sBg, $sColor, $sBorder] = ['#FFFBEB', '#B45309', '#FCD34D'];
    } elseif (str_contains($submissionStatus, 'Respondido')) {
        [$sBg, $sColor, $sBorder] = ['#ECFDF5', '#065F46', '#6EE7B7'];
    } elseif (str_contains($submissionStatus, 'Demorado')) {
        [$sBg, $sColor, $sBorder] = ['#FEF2F2', '#991B1B', '#FCA5A5'];
    } elseif (str_contains($submissionStatus, 'Cerrado')) {
        [$sBg, $sColor, $sBorder] = ['#F3F4F6', '#374151', '#D1D5DB'];
    } else {
        [$sBg, $sColor, $sBorder] = ['#F3F4F6', '#6B7280', '#E5E7EB'];
    }
@endphp

    {{-- Saludo --}}
    <p style="margin:0 0 6px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#FF7500; text-transform:uppercase; letter-spacing:0.8px;">
        Hola,
    </p>
    <h1 style="margin:0 0 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:24px; font-weight:900; color:#000000; line-height:1.2;">
        {{ $formSubmission->user->name }}
    </h1>
    <p style="margin:0 0 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:16px; font-weight:400; color:#374151; line-height:1.6;">
        Recibiste una nueva consulta a través de la plataforma <strong style="color:#000000;">PPA RED</strong>.
        A continuación encontrás los datos completos del solicitante.
    </p>

    {{-- Badge de estado --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding-bottom:28px;">
                <span style="display:inline-block; background-color:{{ $sBg }}; border:1px solid {{ $sBorder }}; border-radius:20px; padding:5px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:{{ $sColor }}; letter-spacing:0.4px; text-transform:uppercase;">
                    Estado: {{ $submissionStatus }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="border-top:2px solid #F2F2F2;"></td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:36px; font-size:1px; line-height:36px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Título sección --}}
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Datos del solicitante
    </p>

    {{-- Tabla de datos del usuario --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="border:1px solid #E5E7EB; border-radius:8px; overflow:hidden;">
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; width:35%; vertical-align:top;">
                Nombre
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:600; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $data['name'] ?? '—' }}
            </td>
        </tr>
        <tr class="data-row">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Email
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                <a href="mailto:{{ $data['email'] ?? '' }}" style="color:#FF7500; text-decoration:none;">{{ $data['email'] ?? '—' }}</a>
            </td>
        </tr>
        @if (!empty($data['phone']))
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Teléfono
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $data['phone'] }}
            </td>
        </tr>
        @endif
        @if ($formSubmission->locality)
        <tr class="data-row">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Localidad
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $formSubmission->locality->name }}
            </td>
        </tr>
        @endif
        @if ($formSubmission->zone)
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Zona
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $formSubmission->zone->name }}
            </td>
        </tr>
        @endif
        @if ($formSubmission->province)
        <tr class="data-row">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; vertical-align:top;">
                Provincia
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; vertical-align:top;">
                {{ $formSubmission->province->name }}
            </td>
        </tr>
        @endif
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Mensaje recibido --}}
    @if (!empty($formResponse->message))
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:44px; font-size:1px; line-height:44px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Mensaje del solicitante
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#FFF7ED; border-left:4px solid #FF7500; border-radius:0 6px 6px 0; padding:20px 24px;">
                <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:400; color:#374151; line-height:1.7; white-space:pre-line;">{{ $formResponse->message }}</p>
            </td>
        </tr>
    </table>
    @endif
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Fecha y referencia --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding:12px 16px; background-color:#F9FAFB; border:1px solid #E5E7EB; border-radius:6px;">
                <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#6B7280; line-height:1.6;">
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
            <td align="center" style="border-radius:6px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%);">
                <a href="{{ route('form_submissions.index') }}" target="_blank"
                   class="btn-primary"
                   style="display:inline-block; padding:14px 40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                    Ver en el Dashboard
                </a>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:48px; font-size:1px; line-height:48px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Nota al pie del contenido --}}
    <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#9CA3AF; text-align:center; line-height:1.6;">
        Tenés 48 horas para responder antes de que la consulta pase a estado <strong>Demorado</strong>.
    </p>

@endsection
