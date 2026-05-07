@extends('emails.layouts.base')

@section('title', 'Recibiste una respuesta — ' . config('app.name'))

@section('preheader', 'Tu consulta en PPA RED fue respondida. Revisá el mensaje a continuación.')

@section('badge', 'Respuesta')

@section('content')

@php
    $submissionStatus = $formResponse->formSubmission?->status?->name ?? '—';
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
    $partner = $formResponse->user;
@endphp

    {{-- Ícono y saludo --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td align="center">
                {{-- Círculo con check icónico --}}
                <div style="display:inline-block; width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg, #FD3C00, #FF7500); text-align:center; line-height:56px; font-size:26px; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; mso-line-height-rule:exactly;">
                    &#10003;
                </div>
            </td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Heading principal --}}
    <h1 style="margin:0 0 8px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:26px; font-weight:900; color:#000000; line-height:1.2; text-align:center;">
        ¡Recibiste una respuesta!
    </h1>
    <p style="margin:0 0 20px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:400; color:#6B7280; line-height:1.6; text-align:center;">
        Hola <strong style="color:#374151;">{{ $data['name'] ?? 'cliente' }}</strong>,
        tu consulta fue respondida por un partner de <strong style="color:#000000;">PPA RED</strong>.
    </p>

    {{-- Badge de estado --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td align="center" style="padding-bottom:28px;">
                <span style="display:inline-block; background-color:{{ $sBg }}; border:1px solid {{ $sBorder }}; border-radius:20px; padding:5px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:{{ $sColor }}; letter-spacing:0.4px; text-transform:uppercase;">
                    Estado: {{ $submissionStatus }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:1px solid #E5E7EB;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Respondido por --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="vertical-align:middle;">
                {{-- Avatar inicial --}}
                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td style="vertical-align:middle;">
                            <div style="display:inline-block; width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg, #FD3C00, #FF7500); text-align:center; line-height:40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:16px; font-weight:900; color:#FFFFFF; mso-line-height-rule:exactly;">
                                {{ mb_strtoupper(mb_substr($formResponse->user->name ?? 'P', 0, 1)) }}
                            </div>
                        </td>
                        <td style="padding-left:12px; vertical-align:middle;">
                            <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:700; color:#111827; line-height:1.2;">
                                {{ $formResponse->user->name ?? 'Partner PPA RED' }}
                            </p>
                            <p style="margin:3px 0 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:400; color:#9CA3AF; line-height:1.2;">
                                Partner PPA RED
                                &nbsp;·&nbsp;
                                {{ $formResponse->created_at->format('d/m/Y H:i') }} hs
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Cuerpo del mensaje (el más destacado) --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#FFF7ED; border:1px solid #FED7AA; border-left:4px solid #FF7500; border-radius:0 8px 8px 0; padding:24px 28px;">
                <p style="margin:0 0 10px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#FF7500; letter-spacing:1px; text-transform:uppercase;">
                    Mensaje del partner PPA RED
                </p>
                <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:16px; font-weight:400; color:#1F2937; line-height:1.8; white-space:pre-line;">{{ $formResponse->message }}</p>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:56px; font-size:1px; line-height:56px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:1px solid #E5E7EB;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:36px; font-size:1px; line-height:36px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- ¿Querés responder? --}}
    <p style="margin:0 0 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        ¿Querés continuar la conversación?
    </p>
    <p style="margin:0 0 32px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:400; color:#374151; line-height:1.7;">
        Podés responder directamente desde nuestra plataforma. Tu consulta queda registrada y podrás hacer el seguimiento
        completo del estado de tu trámite en cualquier momento.
    </p>

    {{-- CTA --}}
    @if (isset($formResponse->formSubmission) && $formResponse->formSubmission?->token)
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td align="center" style="border-radius:6px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%);">
                <a href="{{ route('public.form_submission.show', $formResponse->formSubmission->token) }}"
                   target="_blank"
                   class="btn-primary"
                   style="display:inline-block; padding:14px 40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                    Ver mi consulta y responder
                </a>
            </td>
        </tr>
    </table>
    @else
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td align="center" style="border-radius:6px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%);">
                <a href="{{ url('/') }}"
                   target="_blank"
                   class="btn-primary"
                   style="display:inline-block; padding:14px 40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                    Ir a PPA RED
                </a>
            </td>
        </tr>
    </table>
    @endif
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:48px; font-size:1px; line-height:48px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Datos del partner --}}
    @if ($partner)
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#F9FAFB; border:1px solid #E5E7EB; border-left:3px solid #FF7500; border-radius:0 6px 6px 0; padding:16px 20px;">
                <p style="margin:0 0 10px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#FF7500; letter-spacing:1px; text-transform:uppercase;">
                    Contacto del Partner PPA RED
                </p>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; width:38%; vertical-align:top;">Nombre</td>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">{{ $partner->name }}</td>
                    </tr>
                    @if ($partner->email)
                    <tr>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; vertical-align:top;">Email</td>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">
                            <a href="mailto:{{ $partner->email }}" style="color:#FF7500; text-decoration:none;">{{ $partner->email }}</a>
                        </td>
                    </tr>
                    @endif
                    @if ($partner->phone)
                    <tr>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; vertical-align:top;">Teléfono</td>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">{{ $partner->phone }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:20px; font-size:1px; line-height:20px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>
    @endif

    {{-- Datos de la consulta original --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#F9FAFB; border:1px solid #E5E7EB; border-radius:6px; padding:16px 20px;">
                <p style="margin:0 0 10px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
                    Datos de tu consulta
                </p>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; width:40%; vertical-align:top;">
                            Nombre registrado
                        </td>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">
                            {{ $data['name'] ?? '—' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; vertical-align:top;">
                            Email
                        </td>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">
                            {{ $data['email'] ?? '—' }}
                        </td>
                    </tr>
                    @if (!empty($data['phone']))
                    <tr>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; vertical-align:top;">
                            Teléfono
                        </td>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">
                            {{ $data['phone'] }}
                        </td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    {{-- Aviso de privacidad --}}
    <p style="margin:24px 0 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; color:#9CA3AF; text-align:center; line-height:1.6;">
        Recibiste este correo porque realizaste una consulta en {{ config('app.name') }}.<br>
        Si no fuiste vos, por favor ignorá este mensaje.
    </p>

@endsection
