@extends('templates.app')

@section('title', 'Captain Profile')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    @if($message = session('status'))
    <admin-captain-profile v-bind:message="{{ json_encode(['status' => 'success', 'body' => [$message]]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></admin-captain-profile>
    @elseif($errors->any())
    <admin-captain-profile v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></admin-captain-profile>
    @else
    <admin-captain-profile v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></admin-captain-profile>
    @endif
@endsection