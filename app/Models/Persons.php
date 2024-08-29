<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persons extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'FirstName',
        'LastName',
        'MiddleName',
        'suffix',
        'birthdate',
        'gender',
        'civilStatus',
        'religion',
        'educationalAttainment',
        'course',
        'addressID',
        'employmentDetailsID',
        'tags',
        'passportNo'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
    ];

    /**
     * Get the address associated with the employer.
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'addressID', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'personID', 'id');
    }
    public function employmentDetails()
    {
        return $this->hasOne(EmploymentDetails::class, 'employmentDetailsID', 'id');
    }
}
