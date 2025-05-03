<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <!-- utf-8 funciona para la mayoria de los casos -->
    <meta name="viewport" content="width=device-width" />
    <!-- Forzar la escala inicial no deberia ser necesario -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Use la ultima version (edge) del motor de renderizado IE -->
    <meta name="x-apple-disable-message-reformatting" />
    <!-- Deshabilite la escala automatica en iOS 10 Mail por completo -->
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no" />
    <!-- Dile a iOS que no enlace automaticamente ciertas cadenas de texto. -->
    <title>{{ config('app.name') }}</title>
    <!-- La etiqueta del titulo se muestra en las notificaciones por correo electronico, como Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTA: Si no se requieren fuentes web, las lineas 10 a 27 se pueden eliminar de forma segura. -->

    <!-- Desktop Outlook se ahoga en las referencias de fuentes web y el valor predeterminado es Times New Roman, por lo que forzamos una fuente alternativa segura. -->
    <!--[if mso]>
      <style>
        * {
          font-family: Arial, sans-serif !important;
        }
      </style>
    <![endif]-->

    <!-- Todos los demas clientes obtienen la referencia de la fuente web; algunos renderizaran la fuente y otros fallaran silenciosamente a los fallbacks. Mas sobre eso aqui: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <!-- insertar referencia de fuente web, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style>
        /* Que hace: elimina espacios alrededor del dise&ntilde;o de correo electronico agregado por algunos clientes de correo electronico. */
        /* Cuidado: puede eliminar el relleno / margen y agregar un color de fondo para componer una ventana de respuesta. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* Que hace: detiene a los clientes de correo electronico cambiando el tama&ntilde;o del texto peque&ntilde;o. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* Que hace: Centra el correo electronico en Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* Que hace: obliga a los clientes de correo Samsung Android a usar toda la ventana grafica */
        #MessageViewBody,
        #MessageWebViewDiv {
            width: 100% !important;
        }

        /* Que hace: evita que Outlook agregue espacio adicional a las tablas. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* Que hace: reemplaza el estilo de negrita predeterminado. */
        th {
            font-weight: normal;
        }

        /* Que hace: soluciona el problema de relleno del webkit. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        /* Que hace: evita que Windows 10 Mail subraye enlaces a pesar de CSS en linea. Los estilos para los enlaces subrayados deben estar en linea. */
        a {
            text-decoration: none;
        }

        /* Que hace: utiliza un mejor metodo de representacion al cambiar el tama&ntilde;o de las imagenes en IE. */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Que hace: una solucion para clientes de correo electronico que se entrometen en enlaces activados. */
        a[x-apple-data-detectors],
        /* iOS */
        .unstyle-auto-detected-links a,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* Que hace: evita que Gmail cambie el color del texto en los hilos de conversacion. */
        .im {
            color: inherit !important;
        }

        /* Que hace: evita que Gmail muestre un boton de descarga en imagenes grandes no vinculadas. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        /* Si lo anterior no funciona, agregue una clase .g-img a cualquier imagen en cuestion. */
        img.g-img+div {
            display: none !important;
        }

        /* Que hace: elimina el canal derecho de la aplicacion Gmail para iOS: https://github.com/TedGoas/Cerberus/issues/89 */
        /* Cree una de estas consultas de medios para cada tama&ntilde;o de ventana adicional que desee corregir */

        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            u~div .email-container {
                min-width: 320px !important;
            }
        }

        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            u~div .email-container {
                min-width: 375px !important;
            }
        }

        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            u~div .email-container {
                min-width: 414px !important;
            }
        }
    </style>

    <!-- Que hace: hace que las imagenes de fondo en Outlook de 72ppi se procesen con el tama&ntilde;o correcto. -->
    <!--[if gte mso 9]>
      <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG />
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
      </xml>
    <![endif]-->

    <!-- CSS Reset : END -->

    <!-- Mejoras progresivas : BEGIN -->
    <style>
        /* Que hace: estilos de desplazamiento para botones */
        .button-td,
        .button-a,
        .button-td-secondary,
        .button-a-secondary {
            transition: all 100ms ease-in;
        }

        .button-td-primary:hover,
        .button-a-primary:hover {
            background: #fff5e3 !important;
            border-color: #ff6b00 !important;
            color: #ff6b00 !important;
        }

        .button-td-secondary:hover,
        .button-a-secondary:hover {
            background: #bc272d !important;
            border-color: #bc272d !important;
            color: #ffffff !important;
        }

        /* Media Queries */
        @media screen and (max-width: 580px) {
            .email-container {
                width: 100% !important;
                margin: auto !important;
            }

            /* Que hace: fuerza las celdas de la tabla en filas de ancho completo. */
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }

            /* Y el centro justifica estos. */
            .stack-column-center {
                text-align: center !important;
            }

            /* Que hace: Clase de utilidad generica para centrar. util para imagenes, botones y tablas anidadas. */
            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }

            table.center-on-narrow {
                display: inline-block !important;
            }

            /* Que hace: ajusta los estilos en pantallas peque&ntilde;as para mejorar la legibilidad */
            .email-container h1 {
                font-size: 20px !important;
                margin-bottom: 10px !important;
            }

            .email-container h1 a {
                margin-top: 10px;
            }

            .email-container h1 a {
                margin-bottom: 10px;
            }

            .email-container h2 {
                font-size: 16px !important;
                font-weight: normal !important;
            }

            .email-container p {
                font-size: 16px !important;
                font-weight: normal !important;
            }
        }
    </style>
    <!-- Mejoras progresivas : END -->

    <!-- Estilos solo para outlook no compatibles con tipografia web -->
    <!--[if mso]>
    <style type=”text/css”>
        .fallback-font {
            font-family: Arial, sans-serif!important;
        }
    </style>
    <![endif]-->
    <!-- Estilos solo para outlook no compatibles con tipografia web END -->
</head>
<!--
  El color de fondo del correo electronico (#222222) se define en tres lugares:
    1. Body Tag: para la mayoria de los clientes de correo electronico
    2. Central Tag: para aplicaciones moviles de Gmail e Inbox y versiones web de Gmail, GSuite, Inbox, Yahoo, AOL, Libero, Comcast, freenet, Mail.ru, Orange.fr
    3. mso condicional: para Windows 10 Mail
-->

<body width="100%"
    style="
      margin: 0;
      padding: 0 !important;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
        Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol';
      color: #718096;
      mso-line-height-rule: exactly;
      background-color: #f2f4f6;
    ">
    <center style="width: 100%; background-color: #f2f4f6">
        <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f2f4f6; border-radius: 6px;">
    <tr>
    <td>
    <![endif]-->

        <!-- Texto de encabezado oculto visualmente : BEGIN -->
        <div class="fallback-font"
            style="
          display: none;
          font-size: 1px;
          line-height: 1px;
          max-height: 0px;
          max-width: 0px;
          opacity: 0;
          overflow: hidden;
          mso-hide: all;
          font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
            Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
            'Segoe UI Symbol';
          color: #718096;
        ">
            consulta desde {{ config('app.name') }}
        </div>
        <!-- Texto de encabezado oculto visualmente : END -->

        <!-- Cree un espacio en blanco despues del texto de vista previa deseado para que los clientes de correo electronico no tomen otro texto que distraiga en la vista previa de la bandeja de entrada. Extender segun sea necesario. -->
        <!-- Vista previa Hack de espaciado de texto : BEGIN -->
        <div
            style="
          display: none;
          font-size: 1px;
          line-height: 1px;
          max-height: 0px;
          max-width: 0px;
          opacity: 0;
          overflow: hidden;
          mso-hide: all;
          font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
            Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
            'Segoe UI Symbol';
          color: #718096;
        ">
            &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
        </div>
        <!-- Vista previa Hack de espaciado de texto : END -->

        <!-- Cuerpo del Email : BEGIN -->
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="580"
            style="margin: auto; background-color: #ffffff; border-radius: 6px" class="email-container">
            <!-- Header-->
            <tr>
                <td style="vertical-align: middle">
                    <img src="https://depisos.com/php-whatsapp-chatbot-main/header-email.webp"
                        style="
                margin: 0;
                padding: 0;
                border: none;
                display: block;
                width: 100%;
                height: auto;
              "
                        border="0" alt="header" />
                </td>
            </tr>
            <!-- Header end-->

            <!-- Texto-->
            <tr style="background-color: #ffffff">
                <td style="padding: 40px">
                    <h1 class="fallback-font"
                        style="
                margin: 0 0 30px 0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #3d4852;
                font-size: 22px;
                line-height: 32px;
                text-align: center;
                font-weight: 700;
              ">
                        <b>Nuevo mensaje recibido</b>
                    </h1>

                    <h2 class="fallback-font"
                        style="
                margin: 0 0 5px 0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 19px;
                line-height: 29px;
                text-align: left;
                font-weight: 700;
              ">
                        <b>Hola {{ $formSubmission->user->name }}</b>
                    </h2>

                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 18px;
                line-height: 28px;
                text-align: left;
                font-weight: 400;
              ">
                        Recibiste una nueva consulta desde la plataforma {{ config('app.name') }}.
                    </p>
                    <br />
                    <br />

                    <h3 class="fallback-font"
                        style="
                margin: 0 0 10px 0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: center;
                font-weight: 700;
              ">
                        <b>DATOS DEL MENSAJE:</b>
                    </h3>

                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: left;
                font-weight: 400;
              ">
                        <b>Nombre:</b> {{ $data['name'] }}
                    </p>

                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: left;
                font-weight: 400;
              ">
                        <b>Email:</b> {{ $data['email'] }}
                    </p>

                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: left;
                font-weight: 400;
              ">
                        <b>Tel&eacute;fono:</b> {{ $data['phone'] }}
                    </p>

                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: left;
                font-weight: 400;
              ">
                        <b>Localidad:</b> {{ $formSubmission->locality->name }}
                    </p>

                    @if ($formSubmission->zone)
                        <p class="fallback-font"
                            style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: left;
                font-weight: 400;
              ">
                            <b>Zona:</b> {{ $formSubmission->zone->name }}
                        </p>
                    @endif

                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: left;
                font-weight: 400;
              ">
                        <b>Provincia:</b> {{ $formSubmission->province->name }}
                    </p>

                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: left;
                font-weight: 400;
              ">
                        <b>Mensaje:</b> {{ $formResponse->message }}
                    </p>

                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                text-align: left;
                font-weight: 400;
              ">
                        <b>Fecha:</b> {{ $formResponse->created_at->format('d/m/Y H:i') }}
                    </p>
                </td>
            </tr>
            <!-- Texto end-->

            <!-- Boton : BEGIN -->
            <tr style="background-color: #ff6b00">
                <td
                    style="
              background-color: #ff6b00;
              text-align: center;
              padding: 40px;
            ">
                    <p class="fallback-font"
                        style="
                margin: 0 0 10px;
                margin-bottom: 20px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                color: #718096;
                font-size: 16px;
                line-height: 26px;
                color: #ffffff;
                text-align: center;
                font-weight: 400;
              ">
                        <b>¿Necesitas responder a este usuario?</b>
                    </p>
                    <!-- Button : BEGIN -->
                    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0"
                        style="margin: auto">
                        <tr>
                            <td class="button-td button-td-primary" style="border-radius: 4px; background: #ffffff">
                                <a class="fallback-font button-a button-a-primary" target="_blank" rel="noopener"
                                    href="{{ route('form_submissions.show', $formSubmission->id) }}"
                                    style="
                      background: #ffffff;
                      border: 1px solid #ffffff;
                      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                        Roboto, Helvetica, Arial, sans-serif,
                        'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
                      color: #718096;
                      font-size: 18px;
                      line-height: 28px;
                      font-weight: 700;
                      text-decoration: none;
                      padding: 10px 20px;
                      color: #ff6b00;
                      display: block;
                      border-radius: 4px;
                    ">RESPONDER
                                </a>
                            </td>
                        </tr>
                    </table>
                    <!-- Button : END -->
                </td>
            </tr>
            <!-- Boton : END -->

            <!-- Footer : BEGIN -->
            <tr align="center" style="background-color: #ffffff">
                <td align="center" style="background-color: #ffffff; padding: 10px">
                    <p
                        style="
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                  Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                  'Segoe UI Emoji', 'Segoe UI Symbol';
                box-sizing: border-box;
                line-height: 1.5em;
                margin-top: 0;
                color: #9ba2ab;
                font-size: 12px;
                text-align: center;
              ">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los
                        derechos reservados.
                    </p>
                </td>
            </tr>
            <!-- Footer : END -->
        </table>
        <!--[if mso | IE]>
  </td>
  </tr>
  </table>
  <![endif]-->
    </center>
</body>

</html>
