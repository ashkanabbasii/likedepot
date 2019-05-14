<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;
    protected $table = 'services';
    protected $fillable = [
        'name',

    ];

    public function products(){
        return $this->hasMany("App\\Models\\Product\\Product" , "service_id");
    }
}
