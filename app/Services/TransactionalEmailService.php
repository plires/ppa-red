<?php

namespace App\Services;

use App\Models\TransactionalEmail;

class TransactionalEmailService
{
  protected $emails;

  public function __construct($type)
  {
    // Cargar todos los emails con type "cambio de estado" en memoria
    $this->emails = TransactionalEmail::where('type', $type)->get()
      ->groupBy(function ($email) {
        return $email->title . '_' . $email->recipient_type . '_' . ($email->variant ?? 'default');
      });
  }

  /**
   * Obtener un email según estado, destinatario y variante.
   */

  public function getEmail(string $status, string $recipient, ?string $variant = null)
  {
    $key = $status . '_' . $recipient . '_' . ($variant ?? 'default');
    return $this->emails->get($key)?->first();
  }
}
