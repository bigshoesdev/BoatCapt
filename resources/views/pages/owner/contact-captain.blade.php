@extends('templates.app')

@section('title', 'Contact Captain')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <contact-captain v-bind:trip-info="{{ $tripInfo }}"></contact-captain>
@endsection