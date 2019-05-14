<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'important',
        'view'
    ];

    public function user()
    {
        return $this->belongsTo("App\\Models\\User\\User", "user_id");
    }

    public function replies()
    {
        return $this->hasMany("App\\Models\\Reply\\Reply", "ticket_id");
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($query) {
            $query->replies()->delete();
        });

        static::deleted(function ($query) {
        });
    }
}
