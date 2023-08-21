<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'featured_image',
    ];

    public function getFeaturedImageAttribute($value)
    {
        // If the value starts with "storage/", it means it's coming from the storage
        if (str_starts_with($value, 'images/posts/')) {
            return 'storage/'.$value; // Prepend the asset function to add the base URL
        }

        return $value;
    }
}
