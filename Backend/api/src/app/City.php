<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class City extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'admin_id',
        'name',
        'state',
        'country',
        'photo',
        'lon',
        'lat',
        'kibla',
    ];

    public function time()
    {
        return $this->hasOne(Time::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo == 'default_city_photo.jpg')
            return config('app.url') . Storage::url($this->photo);

        return config('app.url') . Storage::url('cities/' . $this->id . '/' . $this->photo);
    }
}
