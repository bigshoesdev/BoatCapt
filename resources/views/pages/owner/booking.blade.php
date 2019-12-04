@extends('templates.app')

@section('title', 'Owner Booking')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($errors->any())
    <owner-booking v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:bid-info="{{ $bidInfo }}" v-bind:old-input="{{ json_encode(['merchant_type' => old('merchant_type', null)]) }}"></owner-booking>
    @else
    <owner-booking v-bind:bid-info="{{ $bidInfo }}"></owner-booking>
    @endif
@endsection