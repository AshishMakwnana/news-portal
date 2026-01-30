<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'language'];

    protected static function booted()
    {
        static::creating(function ($c) {
            if (empty($c->slug)) {
                $c->slug = Str::slug($c->name);
            }
        });
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }
}
