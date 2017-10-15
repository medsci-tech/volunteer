<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClassDetails extends Model
{
    protected $table = 'mime_class_details';

    protected $fillable = [
        'name',
        'lecturer',
        'published_at',
    ];
}
