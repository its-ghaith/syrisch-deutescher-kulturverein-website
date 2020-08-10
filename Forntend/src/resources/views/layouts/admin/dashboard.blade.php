@extends('layouts.admin.admin_app')
@section('title','Admin Dashboard')
@section('adminContent')

    <div class="container mt-5">
        <div class="card-columns">
            <div class="mb-5">
                <a href="{{url('/admin/times_management/add_new_time')}}" class="">
                    <div class="card">
                        <img class="card-img-top" src="{{asset('img/prayer_time.png')}}" alt="prayer_time">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Add new time')}}</h5>
                            <p class="card-text">{{__('This page allows you to add or modify prayer times for cities managed by you.')}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="mb-5">
                <a href="{{url('/admin/times_management/delete_time')}}">
                    <div class="card">
                        <img class="card-img-top" src="{{asset('img/prayer_time.png')}}" alt="prayer_time">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Delete old time')}}</h5>
                            <p class="card-text">{{__('This page allows you to remove prayer times for cities managed by you.')}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="mb-5">
                <a href="{{url('/admin/times_management/show_cities')}}">
                    <div class="card">
                        <img class="card-img-top" src="{{asset('img/prayer_time.png')}}" alt="prayer_time">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Show all cities')}}</h5>
                            <p class="card-text">{{__('This page allows you to show all prayer times for cities managed by you.')}}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

