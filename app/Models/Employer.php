<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $table = 'employers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'companyName',
        'companyType',
        'same_as',
        'logo',
        'industry',
        'description',
        'mission',
        'vision',
        'addressID',
    ];

    /**
     * Get the address associated with the employer.
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'addressID');
    }
}
