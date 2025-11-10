<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_uid',
        'sent_by',
        'sent_to',
        'seen',
        'message_type',
        'main_message',
    ];
}
