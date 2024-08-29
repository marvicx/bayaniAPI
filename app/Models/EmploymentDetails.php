<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentDetails extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employment_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employerName',
        'employerAddressID',
        'vessel',
        'occupation',
        'monthlySalary',
        'agencyName',
        'contractDuration',
        'ofwType',
        'jobSite',
        'passport_attachment',
        'coe_attachment',
        'status',
    ];

    public function person()
    {
        return $this->hasOne(Persons::class, 'employmentDetailsID', 'id');
    }
}