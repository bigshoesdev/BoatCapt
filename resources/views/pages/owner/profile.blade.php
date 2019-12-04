@extends('templates.app')

@section('title', 'Owner Profile')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($message = session('status'))
    <owner-profile v-bind:message="{{ json_encode(['status' => 'success', 'body' => [$message]]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></owner-profile>
    @elseif($errors->any())
    <owner-profile v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}" v-bind:old-input="{{ json_encode(old()) }}"></owner-profile>
    @else
    <owner-profile v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></owner-profile>
    @endif
@endsection