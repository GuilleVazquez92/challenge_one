<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'isbn', 'author_id', 'published_date'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
