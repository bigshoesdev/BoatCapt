@extends('templates.app')

@section('title', 'Captain Profile')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    @if($message = session('status'))
    <captain-profile v-bind:message="{{ json_encode(['status' => 'success', 'body' => [$message]]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></captain-profile>
    @elseif($errors->any())
    <captain-profile v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}" v-bind:old-input="{{ json_encode(old()) }}"></captain-profile>
    @else
    <captain-profile v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></captain-profile>
    @endif
@endsection