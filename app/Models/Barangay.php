<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory; 
    protected $fillable = [
        'code', 'name', 'oldName', 'subMunicipalityCode', 'cityCode', 'municipalityCode',
        'districtCode', 'provinceCode', 'regionCode', 'islandGroupCode', 'psgc10DigitCode'
    ];
}
