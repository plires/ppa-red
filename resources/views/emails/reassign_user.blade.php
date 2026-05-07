@extends('emails.layouts.base')

@section('title', 'Actualización sobre tu consulta — ' . config('app.name'))

@section('preheader', 'Tu consulta fue asignada a un nuevo especialista de PPA RED.')

@section('badge', 'Actualización')

@section('content')

    {{-- Saludo --}}
    <p style="margin:0 0 6px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#FF7500; text-transform:uppercase; letter-spacing:0.8px;">
        Hola,
    </p>
    <h1 style="margin:0 0 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:24px; font-weight:900; color:#000000; line-height:1.2;">
        {{ $userName }}
    </h1>
    <p style="margin:0 0 28px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:16px; font-weight:400; color:#374151; line-height:1.6;">
        Queremos informarte que tu consulta fue asignada a un nuevo especialista de
        <strong style="color:#000000;">PPA RED</strong>. Tu solicitud sigue activa y será atendida a la brevedad.
        No es necesario que hagas nada — podés continuar el seguimiento desde tu link habitual.
    </p>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:2px solid #F2F2F2;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Nuevo especialista --}}
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Tu nuevo especialista asignado
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="border:1px solid #E5E7EB; border-radius:8px; overflow:hidden;">
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; width:35%; vertical-align:top;">
                Nombre
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:600; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $incoming->name }}
            </td>
        </tr>
        @if ($incoming->email)
        <tr class="data-row">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Email
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                <a href="mailto:{{ $incoming->email }}" style="color:#FF7500; text-decoration:none;">{{ $incoming->email }}</a>
            </td>
        </tr>
        @endif
        @if ($incoming->phone)
        <tr class="data-row" style="background-color:#FAFAFA;">
            <td class="data-label" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; vertical-align:top;">
                Teléfono
            </td>
            <td class="data-value" style="padding:12px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; vertical-align:top;">
                {{ $incoming->phone }}
            </td>
        </tr>
        @endif
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Referencia --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding:12px 16px; background-color:#F9FAFB; border:1px solid #E5E7EB; border-radius:6px;">
                <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#6B7280; line-height:1.6;">
                    <strong style="color:#374151;">Consulta originada el:</strong>
                    {{ $submission->created_at->format('d/m/Y \a \l\a\s H:i') }} hs
                    &nbsp;·&nbsp;
                    <strong style="color:#374151;">Ref. #{{ $submission->id }}</strong>
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:56px; font-size:1px; line-height:56px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- CTA --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td align="center" style="border-radius:6px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%);">
                <a href="{{ route('public.form_submission.show', $submission->secure_token) }}" target="_blank"
                   class="btn-primary"
                   style="display:inline-block; padding:14px 40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                    Ver mi consulta
                </a>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:48px; font-size:1px; line-height:48px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#9CA3AF; text-align:center; line-height:1.6;">
        Si tenés dudas, podés responder directamente desde el link de seguimiento de tu consulta.
    </p>

@endsection
