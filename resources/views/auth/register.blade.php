@extends('templates.app')

@section('title', 'Sign Up')
@section('header')
    <page-header v-bind:dark="true" v-bind:param="{{ json_encode(['avatar' => null, 'searchable' => true, 'login' => false]) }}"></page-header>
@endsection
@section('content')
    @if($errors->any())
    <regist v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:param="{{ json_encode(['account_type' => old('account_type'), 'name' => old('name'), 'email' => old('email')]) }}"></regist>
    @elseif(isset($account_type))
    <regist v-bind:param="{{ json_encode(['account_type' => $account_type == 'owner' ? 1 : ($account_type == 'captain' ? 0 : -1), 'name' => '', 'email' => '']) }}"></regist>
    @else
    <regist v-bind:param="{{ json_encode(['account_type' => -1, 'name' => '', 'email' => '']) }}"></regist>
    @endif
@endsection
