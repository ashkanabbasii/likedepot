<?php

namespace App\Models\NewsLetter;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    protected $table = 'newsletter';
    protected $fillable = [
        'email',

    ];


}
