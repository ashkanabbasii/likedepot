<?php

namespace App\Models\Source;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    public $timestamps = false;
    protected $table = 'sources';
    protected $fillable = [
        'name',
    ];

    public function products(){
        return $this->hasMany("App\\Models\\Product\\Product" , "source_id");
    }
    public function discount(){
        return $this->hasOne('App\\Models\\Discounts\\Discounts','source_id');
    }
}
