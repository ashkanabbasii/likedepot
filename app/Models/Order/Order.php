<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'product_id',
        'user_id',
        'ref_id',
        'price',
        'status',
        'link',
        'view',
        'gender',

    ];

    public function user(){
        return $this->belongsTo("App\\Models\\User\\User" , "user_id");
    }
    public function product(){
        return $this->belongsTo("App\\Models\\Product\\Product" , "product_id");
    }

}
