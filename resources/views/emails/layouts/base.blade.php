<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <title>@yield('title', config('app.name'))</title>

    <!--[if mso]>
    <style type="text/css">
        * { font-family: Arial, Helvetica, sans-serif !important; }
        table { border-collapse: collapse !important; }
    </style>
    <![endif]-->

    <style type="text/css">
        /* CSS Reset */
        html, body { margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important; }
        * { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; }
        div[style*="margin: 16px 0"] { margin: 0 !important; }
        #MessageViewBody, #MessageWebViewDiv { width: 100% !important; }
        table, td { mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important; }
        table { border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important; }
        img { -ms-interpolation-mode: bicubic; border: 0; display: block; outline: none; text-decoration: none; }
        a { text-decoration: none; }
        a[x-apple-data-detectors], .unstyle-auto-detected-links a, .aBn {
            border-bottom: 0 !important; cursor: default !important; color: inherit !important;
            text-decoration: none !important; font-size: inherit !important; font-family: inherit !important;
            font-weight: inherit !important; line-height: inherit !important;
        }
        .im { color: inherit !important; }
        .a6S { display: none !important; opacity: 0.01 !important; }

        /* Hover states */
        .btn-primary:hover { opacity: 0.9 !important; }

        /* Mobile */
        @media screen and (max-width: 600px) {
            .email-wrapper { width: 100% !important; max-width: 100% !important; }
            .email-content { padding: 28px 20px !important; }
            .email-header { padding: 24px 20px !important; }
            .email-footer { padding: 20px !important; }
            .data-table td { display: block !important; width: 100% !important; padding: 8px 16px !important; }
            .data-label { border-bottom: 0 !important; }
            .data-value { padding-top: 0 !important; font-weight: 600 !important; }
        }
    </style>

    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
</head>

<body width="100%" style="margin:0; padding:0 !important; background-color:#F2F2F2; mso-line-height-rule:exactly;">
<center style="width:100%; background-color:#F2F2F2;">

    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F2F2F2;">
    <tr><td>
    <![endif]-->

    {{-- Preheader oculto --}}
    <div style="display:none; font-size:1px; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; mso-hide:all; color:#F2F2F2;">
        @yield('preheader', config('app.name') . ' — Gestión de consultas')
        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>

    {{-- Email container --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center"
           width="600" class="email-wrapper"
           style="margin:0 auto; max-width:600px;">

        {{-- Espaciado superior --}}
        <tr><td style="height:24px; background-color:#F2F2F2;">&nbsp;</td></tr>

        {{-- HEADER: Degradado institucional --}}
        <tr>
            <td class="email-header"
                style="padding:32px 48px 28px; background-color:#FF7500; border-radius:8px 8px 0 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="vertical-align:middle;">
                            <img src="{{ config('app.url') }}/images/email/logo-ppa-red-email.png"
                                 alt="PPA RED"
                                 width="150"
                                 style="display:block; border:0; outline:none; text-decoration:none; height:auto; max-width:150px;">
                        </td>
                        <td align="right" style="vertical-align:middle;">
                            {{-- Badge tipo etiqueta: fallback sólido + rgba() para quien lo soporte --}}
                            <span style="display:inline-block; background-color:#CC5E00; background:rgba(0,0,0,0.2); border-radius:4px; padding:5px 12px; font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#FFFFFF; letter-spacing:0.8px; text-transform:uppercase;">
                                @yield('badge', 'Notificación')
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Barra decorativa delgada --}}
        <tr>
            <td style="height:4px; background-color:#FF7500;"></td>
        </tr>

        {{-- CONTENT --}}
        <tr>
            <td class="email-content"
                style="background-color:#FFFFFF; padding:40px 48px; border-left:1px solid #E5E7EB; border-right:1px solid #E5E7EB;">
                @yield('content')
            </td>
        </tr>

        {{-- FOOTER --}}
        <tr>
            <td class="email-footer"
                style="background-color:#000000; padding:28px 48px; border-radius:0 0 8px 8px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td align="center">
                            <img src="{{ config('app.url') }}/images/email/logo-ppa-red-email.png"
                                 alt="PPA RED"
                                 width="80"
                                 style="display:block; margin:0 auto 12px; border:0; outline:none; text-decoration:none; height:auto; opacity:0.85;">
                            <p style="margin:0 0 12px; font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#6B7280; line-height:1.6;">
                                Este correo fue generado automáticamente · Por favor no respondas este mensaje
                            </p>
                            <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#4B5563;">
                                &copy; {{ date('Y') }} PPA RED. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Espaciado inferior --}}
        <tr><td style="height:24px; background-color:#F2F2F2;">&nbsp;</td></tr>

    </table>

    <!--[if mso | IE]>
    </td></tr></table>
    <![endif]-->

</center>
</body>
</html>
