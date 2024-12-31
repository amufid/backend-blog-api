<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid(); // Mengisi UUID
        });
    }

    public $incrementing = false; // Non-increment
    protected $keyType = 'string';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attachment()
    {
        return $this->hasMany(Attachment::class);
    }
}
