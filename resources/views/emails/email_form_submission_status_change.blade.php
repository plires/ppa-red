@extends('emails.layouts.base')

@section('title', $subject . ' — ' . config('app.name'))

@section('preheader', $subject)

@section('badge', $recipientType === 'partner' ? 'Actualización' : 'Estado de tu consulta')

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

    {{-- Saludo personalizado según destinatario --}}
    @if ($recipientType === 'partner')
        <p style="margin:0 0 6px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#FF7500; text-transform:uppercase; letter-spacing:0.8px;">
            Hola,
        </p>
        <h1 style="margin:0 0 24px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:24px; font-weight:900; color:#000000; line-height:1.2;">
            {{ $partner->name ?? 'Partner' }}
        </h1>
    @else
        <p style="margin:0 0 6px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:13px; font-weight:600; color:#FF7500; text-transform:uppercase; letter-spacing:0.8px;">
            Hola,
        </p>
        <h1 style="margin:0 0 24px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:24px; font-weight:900; color:#000000; line-height:1.2;">
            {{ $dataUser['name'] ?? 'cliente' }}
        </h1>
    @endif

    {{-- Badge de estado --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding-bottom:24px;">
                <span style="display:inline-block; background-color:{{ $sBg }}; border:1px solid {{ $sBorder }}; border-radius:20px; padding:5px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:{{ $sColor }}; letter-spacing:0.4px; text-transform:uppercase;">
                    Estado: {{ $submissionStatus }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Cuerpo dinámico del email (viene de la DB via TransactionalEmailService) --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="background-color:#FFF7ED; border:1px solid #FED7AA; border-left:4px solid #FF7500; border-radius:0 8px 8px 0; padding:24px 28px;">
                <p style="margin:0 0 10px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#FF7500; letter-spacing:1px; text-transform:uppercase;">
                    {{ $subject }}
                </p>
                <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:16px; font-weight:400; color:#1F2937; line-height:1.8; white-space:pre-line;">{{ $body }}</p>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:44px; font-size:1px; line-height:44px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Divider --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr><td style="border-top:1px solid #E5E7EB;"></td></tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:36px; font-size:1px; line-height:36px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    {{-- Datos de la consulta --}}
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Detalle de la consulta
    </p>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="border:1px solid #E5E7EB; border-radius:8px; overflow:hidden;">

        {{-- Datos del solicitante --}}
        <tr style="background-color:#FAFAFA;">
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; width:36%; vertical-align:top;">
                Solicitante
            </td>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:600; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $dataUser['name'] ?? '—' }}
            </td>
        </tr>

        @if (!empty($dataUser['email']))
        <tr>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Email
            </td>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                <a href="mailto:{{ $dataUser['email'] }}" style="color:#FF7500; text-decoration:none;">{{ $dataUser['email'] }}</a>
            </td>
        </tr>
        @endif

        @if (!empty($dataUser['phone']))
        <tr style="background-color:#FAFAFA;">
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Teléfono
            </td>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $dataUser['phone'] }}
            </td>
        </tr>
        @endif

        @if ($formSubmission->locality)
        <tr>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Localidad
            </td>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                {{ $formSubmission->locality->name }}
                @if ($formSubmission->province) · {{ $formSubmission->province->name }} @endif
            </td>
        </tr>
        @endif

        <tr style="background-color:#FAFAFA;">
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                Referencia
            </td>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; border-bottom:1px solid #E5E7EB; vertical-align:top;">
                #{{ $formSubmission->id }}
            </td>
        </tr>

        <tr>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:0.5px; vertical-align:top;">
                Fecha de consulta
            </td>
            <td style="padding:11px 16px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; color:#111827; vertical-align:top;">
                {{ $formSubmission->created_at->format('d/m/Y H:i') }} hs
            </td>
        </tr>
    </table>

    {{-- Historial de respuestas (solo si existen) --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    @if ($responses && $responses->count() > 0)
    <p style="margin:0 0 14px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#9CA3AF; letter-spacing:1px; text-transform:uppercase;">
        Historial de mensajes
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        @foreach ($responses->take(3) as $response)
        @php
            $isPartnerMsg = $response->user !== null;
            $msgSenderName = $isPartnerMsg
                ? ($response->user->name ?? 'Partner PPA RED')
                : ($dataUser['name'] ?? 'Usuario');
            // Alineación: el mensaje del "otro" (la contraparte) va a la derecha
            // Email al usuario → mensajes del partner (otro) a la derecha
            // Email al partner → mensajes del usuario (otro) a la derecha
            $pushRight = ($recipientType === 'user') ? $isPartnerMsg : !$isPartnerMsg;
            $msgBg      = $isPartnerMsg ? '#FFF7ED' : '#F9FAFB';
            $msgBorderL = $isPartnerMsg ? '3px solid #FF7500' : '3px solid #D1D5DB';
            $msgNameColor = $isPartnerMsg ? '#B45309' : '#6B7280';
        @endphp
        <tr>
            <td style="padding:0 0 10px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        @if ($pushRight)<td width="15%">&nbsp;</td>@endif
                        <td width="85%" style="background-color:{{ $msgBg }}; border-left:{{ $msgBorderL }}; border-radius:0 6px 6px 0; padding:12px 16px; vertical-align:top;">
                            <p style="margin:0 0 5px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:{{ $msgNameColor }}; text-transform:uppercase; letter-spacing:0.4px;">
                                {{ $msgSenderName }}
                                <span style="font-weight:400; color:#9CA3AF; margin-left:6px; text-transform:none; letter-spacing:0;">· {{ $response->created_at->format('d/m/Y H:i') }} hs</span>
                            </p>
                            <p style="margin:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:14px; font-weight:400; color:#374151; line-height:1.6;">
                                {{ $response->message }}
                            </p>
                        </td>
                        @if (!$pushRight)<td width="15%">&nbsp;</td>@endif
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach
        @if ($responses->count() > 3)
        <tr>
            <td style="padding:0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#9CA3AF; text-align:center;">
                y {{ $responses->count() - 3 }} mensaje(s) más en el sistema...
            </td>
        </tr>
        @endif
    </table>
    @endif

    {{-- CTA según tipo de destinatario --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:32px; font-size:1px; line-height:32px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>

    @if ($recipientType === 'partner')
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:56px; font-size:1px; line-height:56px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
            <tr>
                <td align="center" style="border-radius:6px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%);">
                    <a href="{{ route('form_submissions.show', $formSubmission->id) }}"
                       target="_blank"
                       class="btn-primary"
                       style="display:inline-block; padding:14px 40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                        Ver consulta en el Dashboard
                    </a>
                </td>
            </tr>
        </table>
    @else
        {{-- Para usuarios públicos: link a su consulta si tienen token --}}
        @if ($formSubmission->token)
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:56px; font-size:1px; line-height:56px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
            <tr>
                <td align="center" style="border-radius:6px; background:linear-gradient(90deg, #FD3C00 0%, #FF7500 100%);">
                    <a href="{{ route('public.form_submission.show', $formSubmission->token) }}"
                       target="_blank"
                       class="btn-primary"
                       style="display:inline-block; padding:14px 40px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:15px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:6px; letter-spacing:0.3px;">
                        Ver estado de mi consulta
                    </a>
                </td>
            </tr>
        </table>
        @endif

        {{-- Datos del Partner para el usuario --}}
        @if ($partner)
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td style="height:28px; font-size:1px; line-height:28px; mso-line-height-rule:exactly;" aria-hidden="true">&nbsp;</td></tr></table>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="background-color:#F9FAFB; border:1px solid #E5E7EB; border-left:3px solid #FF7500; border-radius:0 6px 6px 0; padding:16px 20px;">
                    <p style="margin:0 0 10px; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; color:#FF7500; letter-spacing:1px; text-transform:uppercase;">
                        Contacto del Partner PPA RED.
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
        @endif

        <p style="margin:24px 0 0; font-family:'Noto Sans', Arial, Helvetica, sans-serif; font-size:12px; color:#9CA3AF; text-align:center; line-height:1.6;">
            Recibiste este correo porque realizaste una consulta en {{ config('app.name') }}.<br>
            Si no fuiste vos, por favor ignorá este mensaje.
        </p>
    @endif

@endsection
