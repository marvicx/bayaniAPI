<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'job_categories';

    // Specify which attributes are mass assignable
    protected $fillable = ['name'];


    public function jobPost()
    {
        return $this->hasMany(JobPost::class, 'industry', 'id');
    }
}
