@extends('templates.app')

@section('title', 'Owner Billing Profile')
@section('header')
    <page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($message = session('status'))
    <owner-billing v-bind:message="{{ json_encode(['status' => 'success', 'body' => [$message]]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}" v-bind:owner-id="{{ $ownerId }}"></owner-billing>
    @elseif($errors->has('merchant_type'))
    <owner-billing v-bind:message="{{ json_encode(['status' => 'failed', 'body' => ['Choose payment method']]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}" v-bind:owner-id="{{ $ownerId }}"></owner-billing>
    @elseif($errors->any())
    <owner-billing v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:user-name="{{ $userName }}" v-bind:user-info="{{$userInfo}}" v-bind:old-input="{{ json_encode(['merchant_type' => old('merchant_type', null)]) }}" v-bind:owner-id="{{ $ownerId }}"></owner-billing>
    @else
    <owner-billing v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}" v-bind:owner-id="{{ $ownerId }}"></owner-billing>
    @endif
@endsection