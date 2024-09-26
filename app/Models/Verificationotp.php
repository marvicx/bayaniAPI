<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verificationotp extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'verificationotp';

    // Specify the fillable fields
    protected $fillable = [
        'identifier',
        'token',
        'validity',
        'valid',
    ];

    // Optionally, specify the hidden fields
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
