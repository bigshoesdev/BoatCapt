@extends('templates.app')

@section('title', 'Contact Us')
@section('header')
	<page-header v-bind:dark="true" v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <contact></contact>
@endsection