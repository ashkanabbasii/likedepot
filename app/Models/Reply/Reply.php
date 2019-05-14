<?php

namespace App\Models\Reply;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'ticket_replies';
    protected $fillable = [
        'user_id',
        'ticket_id',
        'reply'

    ];

    public function user(){
        return $this->belongsTo("App\\Models\\User\\User" , "user_id");
    }
    public function ticket(){
        return $this->belongsTo("App\\Models\\Ticket\\Ticket" , "ticket_id");
    }
}
