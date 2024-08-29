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
}