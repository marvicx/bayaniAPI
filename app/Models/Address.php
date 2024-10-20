<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provinceID',
        'regionID',
        'cityID',
        'barangayID',
        'zipcode',
        'street',
        'mobileNo',
        'email',
        'telephoneNo',
        'fax',
        'ofwForeignAddress',
        'ofwCountry',
        'ofwContactNo',
    ];

    public function person()
    {
        return $this->hasOne(Persons::class, 'addressID', 'id');
    }
    public function employer()
    {
        return $this->hasOne(Employer::class, 'addressID', 'id');
    }
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangayID', 'code');
    }

    public function region()
    {
        return $this->belongsTo(Regions::class, 'regionID', 'code');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'provinceID', 'code');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'cityID', 'code');
    }
}