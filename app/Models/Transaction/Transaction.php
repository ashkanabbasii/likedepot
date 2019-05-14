<?php

namespace App\models\Transaction;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $table = 'transaction';

    protected $fillable =['user_id','type','ref_id','ref_type','amount'];

    public function user()
    {
        return $this->belongsTo("App\\Models\\User\\User", "user_id");
    }


}
