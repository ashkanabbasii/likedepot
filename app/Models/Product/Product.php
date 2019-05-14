<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products';
    protected $fillable = [

        'source_id',
        'cat_id',
        'service_id',
        'quantity',
        'price',
        'description',
        'gender',
        'gender_percent'
    ];

    public function service(){
        return $this->belongsTo("App\\Models\\Service\\Service" , "service_id");
    }
    public function category(){
        return $this->belongsTo("App\\Models\\Category\\Category" , "cat_id");
    }
    public function source(){
        return $this->belongsTo("App\\Models\\Source\\Source" , "source_id");
    }

}
