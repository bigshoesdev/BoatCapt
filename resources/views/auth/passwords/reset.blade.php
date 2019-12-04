@extends('templates.app')

@section('title', 'Reset Password')
@section('header')
    <page-header v-bind:dark="true" v-bind:param="{{ json_encode(['avatar' => null, 'searchable' => true, 'login' => false]) }}"></page-header>
@endsection
@section('content')
    @if($errors->any())
    <reset-password v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:param="{{ json_encode(['email' => ($email ? $email : old('email')), 'token' => $token]) }}"></reset-password>
    @else
    <reset-password v-bind:param="{{ json_encode(['email' => '', 'token' => $token]) }}"></reset-password>
    @endif
@endsection
