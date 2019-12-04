@extends('templates.app')

@section('title', 'Captain Trip Detail')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <captain-trip-detail v-bind:trip-info="{{ $tripInfo }}"></captain-trip-detail>
@endsection