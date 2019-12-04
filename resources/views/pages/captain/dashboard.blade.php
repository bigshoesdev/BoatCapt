@extends('templates.app')

@section('title', 'Captain Dashboard')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <captain-dashboard v-bind:user-info="{{ $userInfo }}"></captain-dashboard>
@endsection