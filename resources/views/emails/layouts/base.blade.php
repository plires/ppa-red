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

    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;600;700;900&display=swap" rel="stylesheet" type="text/css">
    <!--<![endif]-->

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
        .data-row:nth-child(even) { background-color: #FAFAFA !important; }

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
                style="padding:32px 48px 28px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%); border-radius:8px 8px 0 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="vertical-align:middle;">
                            <img src="data:image/png;base64,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"
                                 alt="PPA RED"
                                 width="150"
                                 style="display:block; border:0; outline:none; text-decoration:none; height:auto; max-width:150px;">
                        </td>
                        <td align="right" style="vertical-align:middle;">
                            {{-- Badge tipo etiqueta --}}
                            <span style="display:inline-block; background:rgba(0,0,0,0.2); border-radius:4px; padding:5px 12px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#FFFFFF; letter-spacing:0.8px; text-transform:uppercase;">
                                @yield('badge', 'Notificación')
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- Barra decorativa delgada --}}
        <tr>
            <td style="height:4px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%);"></td>
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
                            <img src="data:image/png;base64,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"
                                 alt="PPA RED"
                                 width="80"
                                 style="display:block; margin:0 auto 12px; border:0; outline:none; text-decoration:none; height:auto; opacity:0.85;">
                            <p style="margin:0 0 12px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; color:#6B7280; line-height:1.6;">
                                Este correo fue generado automáticamente · Por favor no respondas este mensaje
                            </p>
                            <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; color:#4B5563;">
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
