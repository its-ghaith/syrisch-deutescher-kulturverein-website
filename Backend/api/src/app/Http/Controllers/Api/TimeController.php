<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\Http\Controllers\Controller;
use App\Http\Resources\TimeResource;
use App\Time;
use Illuminate\Http\Request;

class TimeController extends Controller
{

    public function index()
    {
        $times = TimeResource::collection(Time::paginate(25));
        return $times;
    }


    public function show(City $city)
    {
        return new TimeResource($city->time);
    }


}
