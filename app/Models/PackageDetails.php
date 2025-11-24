<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_of',
        'service_name',
        'service_available',
    ];
}
