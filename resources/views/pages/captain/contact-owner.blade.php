@extends('templates.app')

@section('title', 'Contact Owner')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <contact-owner v-bind:trip-info="{{ $tripInfo }}"></contact-owner>
@endsection