<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable=[
        'user_id',
        'token',
        'expires_at',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
