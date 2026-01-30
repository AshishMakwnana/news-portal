<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'thumbnail',
        'disk',
        'mime_type',
        'size',
        'alt',
        'collection',
    ];

    protected $appends = ['url', 'thumbnail_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->filename);
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        // fall back to the original image
        return $this->url;
    }
}
