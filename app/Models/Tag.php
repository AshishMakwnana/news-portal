<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','language'];

    protected static function booted()
    {
        static::creating(function ($t) {
            if (empty($t->slug)) {
                $t->slug = Str::slug($t->name);
            }
        });
    }

    public function news()
    {
        return $this->belongsToMany(News::class);
    }
}
