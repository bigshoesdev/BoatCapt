@extends('templates.app')

@section('title', 'Owner Profile')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    @if($message = session('status'))
    <admin-owner-profile v-bind:message="{{ json_encode(['status' => 'success', 'body' => [$message]]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></admin-owner-profile>
    @elseif($errors->any())
    <admin-owner-profile v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></admin-owner-profile>
    @else
    <admin-owner-profile v-bind:user-info="{{$userInfo}}" v-bind:user-name="{{ $userName }}"></admin-owner-profile>
    @endif
@endsection