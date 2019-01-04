<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    //
    protected $fillable = [
        'name', 'filename', 'path', 'user_id'
    ];
}
