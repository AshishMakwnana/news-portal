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
        'disk',
        'mime_type',
        'size',
        'alt',
        'collection',
    ];

    protected $appends = ['url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->filename);
    }

    public function getThumbnailAttribute()
    {
        return $this->url; // placeholder, could be a resized version
    }
}
