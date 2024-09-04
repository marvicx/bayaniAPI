<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'event_images';

    // The attributes that are mass assignable.
    protected $fillable = [
        'userID',
        'postID',
        'image',
        'path',
    ];

    // Define relationships with other models, if applicable.

    /**
     * Get the user that owns the event image.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Get the post that the event image belongs to.
     */
    public function post()
    {
        return $this->belongsTo(InformationPost::class, 'id');
    }
}
