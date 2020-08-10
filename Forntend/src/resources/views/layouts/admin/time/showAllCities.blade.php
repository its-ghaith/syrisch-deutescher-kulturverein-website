@extends('layouts.admin.admin_app')
@section('title','Show all cities')
@section('adminContent')
    <div class="container mt-5">
        <div class="card-columns">
            @foreach($response['data'] as $city)
                <div class="mb-5">
                    <a href="{{url('/admin/times_management/add_new_time')}}" data-city-id="{{$city['id']}}"
                       data-city-time-url="{{$city['time']['city_time_url'] ?? ''}}">
                        <div class="card">
                            <img class="card-img-top" src="{{$city['photo_url']}}" alt="{{$city['name'].'-photo'}}">
                            <div class="card-body">
                                <h5 class="card-title">{{$city['name']}}</h5>
                                <p class="card-text">{{__('country')}}: {{$city['country']}}</p>
                                <p class="card-text">{{__('state')}}: {{$city['state']}}</p>
                                <p class="card-text">{{__('Latitude')}}: {{$city['lat']}}</p>
                                <p class="card-text">{{__('Longitude')}}: {{$city['lon']}}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

