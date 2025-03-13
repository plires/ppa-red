<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionalEmail extends Model
{
    use HasFactory;

    protected $table = 'transactional_emails';

    protected $fillable = [
        'recipient_type',
        'title',
        'subject',
        'body',
        'type',
        'variant',
    ];
}
