<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'page_uid',
        'title',
        'snippet',
        'full_text',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            $news->created = Carbon::now();
        });
    }
}
