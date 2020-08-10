@extends('layouts.app')
@php
    $title= current_page('login')?'login' :'register'
@endphp
@section('title',$title)
@section('content')
    <div id="body-container" class="container" style="margin-top: 30px; min-height: 448px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-2 py-5 auth">
                    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{current_page('login')? 'active':''}}" id="login-tab"
                               data-toggle="tab" title="{{__('Login')}}" role="tab"
                               href="{{url('#login')}}">{{__('Login')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{current_page('register')? 'active':''}} " data-toggle="tab"
                               title="{{__('Register')}}"
                               role="tab"
                               href="{{url('#register')}}">{{__('Register')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{current_page('login')? 'active':''}}" id="login" role="tabpanel">
                            <div class="card p-3 p-md-5 rounded-0 border-top-0">
                                <div class="card-body pt-3">
                                    @include('_partisals._login_form')
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{current_page('register')? 'active':''}}" id="register" role="tabpanel">
                            <div class="card p-3 p-md-5 rounded-0 border-top-0">
                                <div class="card-body pt-3">
                                    @include('_partisals._register_form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
