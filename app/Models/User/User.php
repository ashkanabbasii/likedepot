<?php

namespace App\Models\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    protected $fillable = [
        'name','username' ,'wallet' ,'email', 'password', 'type','password_token','status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


    public function orders(){
        return $this->hasMany("App\\Models\\Order\\Order" , "user_id");
    }

    public function replies(){
        return $this->hasMany("App\\Models\\Reply\\Reply" , "user_id");
    }
    public function tickets(){
        return $this->hasMany("App\\Models\\Ticket\\Ticket" , "user_id");
    }

    public function setPasswordAttribute($password)
    {
       return $this->attributes['password'] = Hash::make($password);
    }

    public function checkPasswordAttribute($password){

        return $this->attributes['password'] ? Hash::check($password , $this->attributes['password'] ) : true;
    }

    public function AauthAcessToken(){
        return $this->hasMany('\App\OauthAccessToken');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($query) {
            $query->tickets()->delete();
            $query->replies()->delete();
            $query->orders()->delete();
        });

        static::deleted(function($query) {
        });
    }

}

