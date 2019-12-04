@extends('templates.app')

@section('title', 'Owner Trips')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <owner-trips v-bind:user-info="{{ $userInfo }}"></owner-trips>
@endsection