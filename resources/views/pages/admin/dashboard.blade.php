@extends('templates.app')

@section('title', 'Admin Dashboard')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-dashboard v-bind:new-trips-count="{{ $newTripsCount }}"></admin-dashboard>
@endsection