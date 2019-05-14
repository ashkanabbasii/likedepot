<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
    ];
    public $timestamps = false;

    public function products(){
        return $this->hasMany("App\\Models\\Product\\Product" , "cat_id");
    }
}
