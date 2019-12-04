@extends('templates.app')

@section('title', 'Owner Dashboard')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <owner-dashboard v-bind:user-info="{{ $userInfo }}"></owner-dashboard>
@endsection