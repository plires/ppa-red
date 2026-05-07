@extends('emails.layouts.base')

@section('title', 'Consulta reasignada — ' . config('app.name'))

@section('preheader', 'Una de tus consultas asignadas fue transferida a otro partner PPA RED.')

@section('badge', 'Reasignación')

@section('content')

    {{-- Saludo --}}
    <p style="margin:0 0 6px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#FF7500; text-transform:uppercase; letter-spacing:0.8px;">
        Hola,
    </p>
    <h1 style="margin:0 0 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:24px; font-weight:900; color:#000000; line-height:1.2;">
        {{ $outgoing->name }}
    </h1>
    <p style="margin:0 0 28px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:16px; font-weight:400; color:#374151; line-height:1.6;">
        Te informamos que la consulta de <strong style="color:#000000;">{{ $data['name'] ?? '—' }}</strong>
        que tenías asignada fue <strong style="color:#000000;">reasignada</strong> a otro partner de la red PPA RED.
        No necesitás realizar ninguna acción.
    </p>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:2px solid #F2F2F2;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Datos de la consulta --}}
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Detalle de la consulta
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="border:1px solid #E5E7EB; border-radius:8px; overflow:hidden;">
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; width:38%; vertical-align:top;">
                Solicitante
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:600; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $data['name'] ?? '—' }}
            </td>
        </tr>
        <tr class="data-row">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Localidad
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $submission->locality->name ?? '—' }}{{ $submission->province ? ', ' . $submission->province->name : '' }}
            </td>
        </tr>
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; vertical-align:top;">
                Nuevo responsable
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; vertical-align:top;">
                {{ $incoming->name }}
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Referencia --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding:12px 16px; background-color:#F9FAFB; border:1px solid #E5E7EB; border-radius:6px;">
                <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#6B7280; line-height:1.6;">
                    <strong style="color:#374151;">Fecha de reasignación:</strong>
                    {{ now()->format('d/m/Y \a \l\a\s H:i') }} hs
                    &nbsp;·&nbsp;
                    <strong style="color:#374151;">Ref. #{{ $submission->id }}</strong>
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:48px; font-size:1px; line-height:48px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#9CA3AF; text-align:center; line-height:1.6;">
        Si creés que esto es un error, contactá al administrador de PPA RED.
    </p>

@endsection
