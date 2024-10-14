<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'oldName',
        'isCapital',
        'isCity',
        'isMunicipality',
        'districtCode',
        'provinceCode',
        'regionCode',
        'islandGroupCode',
        'psgc10DigitCode',
    ];

    // Optionally, you can define relationships here
}
