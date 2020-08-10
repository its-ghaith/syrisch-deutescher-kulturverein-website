@extends('layouts.app')
@php

@endphp
@section('content')
    @hasRole(['admin'])
        @yield('adminContent')
    @endhasRole
@endsection
