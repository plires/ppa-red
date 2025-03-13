<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionalEmail;
use App\Models\FormSubmissionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionalEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = [
            // Estado: Pendiente de Respuesta Del Partner
            [
                'recipient_type' => 'partner',
                'title' => FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
                'subject' => 'Tenés una nueva consulta para responder.',
                'type' => 'cambio de estado',
                'variant' => null,
                'body' => '<p><strong>Recibiste una nueva consulta de un usuario. Por favor, revisa el mensaje y responde a la brevedad para evitar demoras en la gestión.</p><p>El sistema cambia automáticamente el estado de la consulta a "Demorado" luego de 48 Hs sin responder al usuario.</p>',
            ],
            [
                'recipient_type' => 'user',
                'title' => FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER,
                'subject' => 'Gracias por tu contacto.',
                'type' => 'cambio de estado',
                'variant' => null,
                'body' => '<p>Tu consulta ha sido enviada correctamente y estamos esperando la respuesta del partner. Te avisaremos cuando tengamos novedades.</p>',
            ],

            // Estado: Respondido por el partner
            [
                'recipient_type' => 'partner',
                'title' => FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
                'subject' => 'Tu respuesta ha sido enviada al usuario.',
                'type' => 'cambio de estado',
                'variant' => null,
                'body' => '<p>Tu respuesta ha sido enviada al usuario. Si necesita más información, podrá responder a tu mensaje. También podés contactarlo por teléfono si lo preferís.</p>',
            ],
            [
                'recipient_type' => 'user',
                'title' => FormSubmissionStatus::STATUS_RESPONDIO_PARTNER,
                'subject' => '¡Tienes una nueva respuesta a tu consulta!',
                'type' => 'cambio de estado',
                'variant' => null,
                'body' => '<p>El partner ha respondido a tu consulta. Podés revisar la respuesta y, si es necesario, continuar con la conversación. Si necesitás más información o tenés dudas adicionales, no dudes en responder el mensaje.</p>',
            ],

            // Estado: Demorado - Sin Respuesta Del Partner (48h)
            [
                'recipient_type' => 'partner',
                'title' => FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER,
                'subject' => 'Tenés una consulta pendiente sin respuesta hace 48 Hs.',
                'type' => 'cambio de estado',
                'variant' => null,
                'body' => '<p>Han pasado más de 48 horas y aún no respondiste la consulta. Por favor, respondé cuanto antes para evitar el cierre automático del formulario.</p>',
            ],

            // Estado: Cerrado - Sin Respuesta Del Partner
            [
                'recipient_type' => 'partner',
                'title' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'subject' => 'Cierre automático: Nunca respondiste la consulta.',
                'type' => 'cambio de estado',
                'variant' => 'nunca_respondio',
                'body' => '<p>Nunca respondiste a la consulta del usuario. Debido a la inactividad en un plazo de 7 días, la consulta ha sido cerrada automáticamente. Si aún querés responder, podés hacerlo a los datos del usuario ya informados.</p>',
            ],
            [
                'recipient_type' => 'partner',
                'title' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'subject' => 'Cierre automático por falta de respuesta.',
                'type' => 'cambio de estado',
                'variant' => 'respondio_antes',
                'body' => '<p>Debido a la falta de respuesta en un plazo de 7 días, la consulta ha sido cerrada automáticamente. Si aún necesitás responder, podés hacerlo a los datos del usuario ya informados.</p>',

            ],
            [
                'recipient_type' => 'user',
                'title' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
                'subject' => 'Tu consulta ha sido cerrada por inactividad.',
                'type' => 'cambio de estado',
                'variant' => null,
                'body' => '<p>Esta consulta se ha cerrado automáticamente tras 7 días sin interacción.</p>',

            ],

            // Estado: Cerrado - Sin Respuesta Del Usuario
            [
                'recipient_type' => 'partner',
                'title' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'subject' => 'Una consulta ha sido cerrada por falta de respuesta del usuario.',
                'type' => 'cambio de estado',
                'variant' => 'respondio_antes',
                'body' => '<p>Debido a la falta de respuesta del usuario en un plazo de 7 días, la consulta ha sido cerrada automáticamente. Si aún necesitás responder, podés hacerlo a los datos del usuario ya informados.</p>',
            ],
            [
                'recipient_type' => 'partner',
                'title' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'subject' => 'Cierre automático por falta total de interacción del usuario.',
                'type' => 'cambio de estado',
                'variant' => 'nunca_respondio',
                'body' => '<p>El usuario no respondió dentro del plazo de 7 días, por lo que la consulta ha sido cerrada automáticamente. El usuario sólo envió un primer mensaje sin continuar la conversación, es posible que haya perdido interés, encontrado una solución alternativa o que el contacto prosiguió telefónicamente.</p>',
            ],
            [
                'recipient_type' => 'user',
                'title' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'subject' => 'Cerramos tu consulta por falta de actividad.',
                'type' => 'cambio de estado',
                'variant' => 'respondio_antes',
                'body' => '<p>Debido a la falta de respuesta en los últimos 7 días, hemos cerrado tu consulta. Si sigues necesitando ayuda, puedes abrir una nueva solicitud o contactar a tu partner asignado a los datos aquí informados.</p>',
            ],
            [
                'recipient_type' => 'user',
                'title' => FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
                'subject' => 'Cerramos tu consulta por inactividad total.',
                'type' => 'cambio de estado',
                'variant' => 'nunca_respondio',
                'body' => '<p>Debido a la falta de respuesta en los últimos 7 días, hemos cerrado tu consulta. Si sigues necesitando ayuda, puedes abrir una nueva solicitud o contactar a tu partner asignado a los datos aquí informados.</p><p>Esta consulta estuvo inactiva durante 7 días, sin ninguna iteracción por parte del usuario.</p>',
            ],

            // Estado: Cerrado Por El Partner
            [
                'recipient_type' => 'partner',
                'title' => FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER,
                'subject' => 'Cerraste una consulta.',
                'type' => 'cambio de estado',
                'variant' => null,
                'body' => '<p>Decidiste cerrar esta consulta. Asegurate de haber proporcionado toda la información necesaria antes de finalizarla.</p>',
            ],
            [
                'recipient_type' => 'user',
                'title' => FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER,
                'subject' => 'Tu consulta ha sido cerrada por el partner.',
                'type' => 'cambio de estado',
                'variant' => null,
                'body' => '<p>El partner ha cerrado la consulta. Si necesitas más información o crees que fue un error, puedes abrir una nueva solicitud o contactar a tu partner asignado a los datos aquí informados.</p>',
            ],

            // Ejemplo de Notificación
            [
                'recipient_type' => 'partner',
                'title' => null,
                'subject' => 'Mantenimiento programado el 15/03',
                'type' => 'notificacion',
                'variant' => 'mantenimiento',
                'body' => '<p>La plataforma estará en mantenimiento el 15 de marzo a las 02:00 AM.</p>',
            ],
        ];

        foreach ($emails as $email) {
            TransactionalEmail::create($email);
        }
    }
}
