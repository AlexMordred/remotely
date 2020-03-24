<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'page_uid',
        'title',
        'snippet',
        'full_text',
    ];
}
