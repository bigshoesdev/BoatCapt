@extends('templates.app')

@section('title', 'Captain Billing Profile')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($message = session('status'))
    <captain-billing v-bind:message="{{ json_encode(['status' => 'success', 'body' => [$message]]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}" v-bind:captain-id="{{ $captainId }}"></captain-billing>
    @elseif($errors->has('merchant_type'))
    <captain-billing v-bind:message="{{ json_encode(['status' => 'failed', 'body' => ['Choose payment method']]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}" v-bind:captain-id="{{ $captainId }}"></captain-billing>
    @elseif($errors->any())
    <captain-billing v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:user-name="{{ $userName }}" v-bind:user-info="{{$userInfo}}" v-bind:old-input="{{ json_encode(['merchant_type' => old('merchant_type', null)]) }}" v-bind:captain-id="{{ $captainId }}"></captain-billing>
    @else
    <captain-billing v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}" v-bind:captain-id="{{ $captainId }}"></captain-billing>
    @endif
@endsection