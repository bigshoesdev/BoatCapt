@extends('templates.app')

@section('title', 'Captain Trips')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <captain-trips v-bind:user-info="{{ $userInfo }}"></captain-trips>
@endsection