<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;


class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "posts";

    protected $fillable = [
        'title', 
        'content', 
        'slug',
        'status',
        'published_at', 
        'cover_image',
        'tags',
        'meta'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        'meta' => 'array'
    ];

    public function categories(){
        //Tabla Pivote post_category
        return $this->belongsToMany(Category::class)->using(CategoryPost::class)->withTimeStamps();
    }
}
