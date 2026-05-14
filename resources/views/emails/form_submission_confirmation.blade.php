@extends('emails.layouts.base')

@section('title', 'Recibimos tu consulta — ' . config('app.name'))

@section('preheader', 'Gracias por contactarte con PPA RED. Tu instalador asignado se comunicará con vos a la brevedad.')

@section('badge', 'Confirmación')

@section('content')

    {{-- Ícono de confirmación --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td align="center">
                <div style="display:inline-block; width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg, #FD3C00, #FF7500); text-align:center; line-height:64px; font-size:30px; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; mso-line-height-rule:exactly;">
                    &#10003;
                </div>
            </td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Heading principal --}}
    <h1 style="margin:0 0 8px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:26px; font-weight:900; color:#000000; line-height:1.2; text-align:center;">
        ¡Gracias por tu consulta!
    </h1>
    <p style="margin:0 0 32px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:400; color:#6B7280; line-height:1.7; text-align:center;">
        Hola <strong style="color:#374151;">{{ $data['name'] ?? 'cliente' }}</strong>,
        recibimos tu consulta correctamente.<br>
        En breve tu instalador asignado de <strong style="color:#000000;">PPA RED</strong> se estará
        comunicando con vos para atender tus requerimientos.
    </p>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:1px solid #E5E7EB;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Datos del instalador asignado --}}
    @if ($partner)
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Tu instalador asignado
    </p>

    {{-- Avatar e info del partner --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#FFF7ED; border:1px solid #FED7AA; border-left:4px solid #FF7500; border-radius:0 8px 8px 0; padding:20px 24px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="vertical-align:middle; width:52px;">
                            <div style="display:inline-block; width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg, #FD3C00, #FF7500); text-align:center; line-height:44px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:18px; font-weight:900; color:#FFFFFF; mso-line-height-rule:exactly;">
                                {{ mb_strtoupper(mb_substr($partner->name ?? 'P', 0, 1)) }}
                            </div>
                        </td>
                        <td style="padding-left:14px; vertical-align:middle;">
                            <p style="margin:0 0 2px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#111827; line-height:1.3;">
                                {{ $partner->name }}
                            </p>
                            <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:400; color:#9CA3AF; line-height:1.3;">
                                Instalador Partner PPA RED
                            </p>
                        </td>
                    </tr>
                </table>

                @if ($partner->email || $partner->phone)
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:16px; border-top:1px solid #FED7AA; padding-top:16px;">
                    @if ($partner->email)
                    <tr>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#92400E; width:30%; vertical-align:top;">Email</td>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">
                            <a href="mailto:{{ $partner->email }}" style="color:#FF7500; text-decoration:none;">{{ $partner->email }}</a>
                        </td>
                    </tr>
                    @endif
                    @if ($partner->phone)
                    <tr>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#92400E; vertical-align:top;">Teléfono</td>
                        <td style="padding:4px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">{{ $partner->phone }}</td>
                    </tr>
                    @endif
                </table>
                @endif
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:36px; font-size:1px; line-height:36px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>
    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:1px solid #E5E7EB;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:36px; font-size:1px; line-height:36px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>
    @endif

    {{-- Sección seguimiento --}}
    <p style="margin:0 0 10px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Seguimiento de tu consulta
    </p>
    <p style="margin:0 0 28px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:400; color:#374151; line-height:1.75;">
        Podés acceder al estado de tu trámite y comunicarte directamente con tu instalador desde nuestra plataforma de seguimiento.
        Allí vas a poder ver las respuestas de tu instalador, enviar mensajes y estar al tanto de cada novedad en tiempo real.
    </p>

    {{-- CTA --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td align="center" style="border-radius:6px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%);">
                <a href="{{ route('public.form_submission.show', $formSubmission->secure_token) }}"
                   target="_blank"
                   class="btn-primary"
                   style="display:inline-block; padding:14px 40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                    Ver el estado de mi consulta
                </a>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:40px; font-size:1px; line-height:40px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:1px solid #E5E7EB;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Resumen de la consulta --}}
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Resumen de tu consulta
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#F9FAFB; border:1px solid #E5E7EB; border-radius:6px; padding:16px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:5px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; width:35%; vertical-align:top;">Nombre</td>
                        <td style="padding:5px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">{{ $data['name'] ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; vertical-align:top;">Email</td>
                        <td style="padding:5px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">{{ $data['email'] ?? '—' }}</td>
                    </tr>
                    @if (!empty($data['phone']))
                    <tr>
                        <td style="padding:5px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; vertical-align:top;">Teléfono</td>
                        <td style="padding:5px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#111827; vertical-align:top;">{{ $data['phone'] }}</td>
                    </tr>
                    @endif
                    @if (!empty($data['message']))
                    <tr>
                        <td style="padding:5px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; color:#6B7280; vertical-align:top;">Consulta</td>
                        <td style="padding:5px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:400; color:#374151; vertical-align:top; line-height:1.6; white-space:pre-line;">{{ $data['message'] }}</td>
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
