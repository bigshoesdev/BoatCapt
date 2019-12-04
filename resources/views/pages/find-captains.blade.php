@extends('templates.app')

@section('title', 'Find Captain')
@section('header')
	<page-header v-bind:dark="true" v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <find-captains v-bind:info="{{ $info }}"></find-captains>
@endsection