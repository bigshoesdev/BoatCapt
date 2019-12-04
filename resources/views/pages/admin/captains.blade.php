@extends('templates.app')

@section('title', 'Admin Captains')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-capts v-bind:new-captains="{{$newCaptains}}"></admin-capts>
@endsection