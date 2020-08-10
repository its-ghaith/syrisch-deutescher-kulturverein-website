<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Time extends Model
{
    protected $fillable=[
        'admin_id',
        'city_id',
        'calc_method',
        'time_file_name',
    ];

    protected $appends = ['times_url'];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function admin(){
       return $this->belongsTo(User::class);
    }

    public function getTimesUrlAttribute(){
        return config('app.url').Storage::url('cities/'.$this->city_id.'/'.$this->time_file_name);
    }

}
