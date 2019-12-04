@extends('templates.app')

@section('title', 'Login')
@section('header')
	<page-header v-bind:dark="true" v-bind:param="{{ json_encode(['avatar' => null, 'searchable' => true, 'login' => false]) }}"></page-header>
@endsection
@section('content')
    @if($errors->any())  
    <login v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:param="{{ json_encode(['email' => old('email')]) }}"></login>
    @else
    <login v-bind:param="{{ json_encode(['email' => '']) }}"></login>
    @endif
@endsection
