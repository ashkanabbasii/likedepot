<?php

namespace App\Models\Discounts;

use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    public $timestamps = false;
    protected $table = 'discount';
    protected $fillable = [
        'status',
        'discount',
        'source_id',
    ];

    public function source(){
        return $this->belongsTo("App\\Models\\Source\\Source" , "source_id");
    }

}
