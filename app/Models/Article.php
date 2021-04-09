<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'title',
        'article_text',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
