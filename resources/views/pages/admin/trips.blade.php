@extends('templates.app')

@section('title', 'Admin Trips')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-trips v-bind:new-count="{{ $newTripsCount }}"></admin-trips>
@endsection