<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'regionCode',
        'islandGroupCode',
        'psgc10DigitCode',
    ];

    // Optionally, you can define relationships here
}
