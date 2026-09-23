@extends('emails.layouts.base')

@section('title', '¡Bienvenido a PPA RED!')

@section('preheader', 'Activá tu cuenta de partner y empezá a gestionar tus consultas asignadas.')

@section('badge', 'Bienvenida')

@section('content')

    {{-- Ícono de bienvenida --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td align="center">
                <div style="display:inline-block; width:64px; height:64px; border-radius:50%; background:#FF7500; text-align:center; line-height:64px; font-size:28px; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; mso-line-height-rule:exactly;">
                    &#128075;
                </div>
            </td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Heading principal --}}
    <h1 style="margin:0 0 8px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:26px; font-weight:900; color:#000000; line-height:1.2; text-align:center;">
        ¡Bienvenido a PPA RED, {{ $partner->name }}!
    </h1>
    <p style="margin:0 0 32px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:400; color:#6B7280; line-height:1.7; text-align:center;">
        Un administrador te dio de alta como <strong style="color:#000000;">partner</strong> en la plataforma de gestión
        de consultas de PPA RED. Para empezar a operar, activá tu cuenta configurando tu propia contraseña.
    </p>

    {{-- CTA --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td align="center" style="border-radius:6px; background:#FF7500;">
                <a href="{{ $setPasswordUrl }}"
                   target="_blank"
                   class="btn-primary"
                   style="display:inline-block; padding:14px 40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                    Configurar mi contraseña
                </a>
            </td>
        </tr>
    </table>
    <p style="margin:16px 0 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#9CA3AF; text-align:center; line-height:1.6;">
        Este enlace es personal y expira en 60 minutos por seguridad.
    </p>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:36px; font-size:1px; line-height:36px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:1px solid #E5E7EB;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Cómo empezar --}}
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Cómo empezar
    </p>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#F9FAFB; border:1px solid #E5E7EB; border-radius:6px; padding:20px 24px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:6px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:700; color:#FF7500; width:28px; vertical-align:top;">1.</td>
                        <td style="padding:6px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#374151; vertical-align:top; line-height:1.6;">Configurá tu contraseña desde el botón de arriba.</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:700; color:#FF7500; width:28px; vertical-align:top;">2.</td>
                        <td style="padding:6px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#374151; vertical-align:top; line-height:1.6;">Iniciá sesión en la plataforma con tu email y la contraseña que elegiste.</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:700; color:#FF7500; width:28px; vertical-align:top;">3.</td>
                        <td style="padding:6px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#374151; vertical-align:top; line-height:1.6;">Completá los datos de tu perfil desde el panel.</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:700; color:#FF7500; width:28px; vertical-align:top;">4.</td>
                        <td style="padding:6px 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#374151; vertical-align:top; line-height:1.6;">Ya vas a poder ver y responder las consultas que te asignen.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Aviso --}}
    <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; color:#9CA3AF; text-align:center; line-height:1.6;">
        Recibiste este correo porque un administrador te registró como partner en {{ config('app.name') }}.<br>
        Si no esperabas este mensaje, podés ignorarlo.
    </p>

@endsection
