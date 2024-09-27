<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'date_posted',
        'valid_through',
        'employment_type',
        'hiring_organization_name',
        'hiring_organization_same_as',
        'hiring_organization_logo',
        'job_location_street_address',
        'job_location_address_locality',
        'job_location_address_region',
        'job_location_postal_code',
        'job_location_address_country',
        'base_salary_value',
        'base_salary_currency',
        'base_salary_unit_text',
        'job_benefits',
        'responsibilities',
        'qualifications',
        'skills',
        'industry',
        'applicant_location_requirements',
        'job_location_type',
        'work_hours',
        'tags',
        'status',
        'postedby',
        'comments'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_posted' => 'date',
        'valid_through' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'postedby', 'id');
    }
}
