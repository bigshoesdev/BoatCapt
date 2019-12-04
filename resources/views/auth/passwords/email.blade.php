@extends('templates.app')

@section('title', 'Forgot Password')
@section('header')
    <page-header v-bind:dark="true" v-bind:param="{{ json_encode(['avatar' => null, 'searchable' => true, 'login' => false]) }}"></page-header>
@endsection
@section('content')
    @if(session('status'))
    <forgot-password v-bind:success="true"></forgot-password>
    @endif
    @if($errors->any())
    <forgot-password v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}"></forgot-password>
    @else
    <forgot-password></forgot-password>
    @endif
@endsection
