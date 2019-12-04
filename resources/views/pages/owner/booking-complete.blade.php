@extends('templates.app')

@section('title', 'Booking Complete')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <owner-booking-complete v-bind:bid-info="{{ $bidInfo }}" v-bind:email="{{ $email }}"></owner-booking-complete>
@endsection