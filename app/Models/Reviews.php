<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_uid',
        'manufacturer_uid',
        'rating',
        'review_text',
        'status',
    ];
}
