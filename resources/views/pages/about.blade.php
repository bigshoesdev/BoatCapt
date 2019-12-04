@extends('templates.app')

@section('title', 'About Us')
@section('header')
	<page-header v-bind:dark="true" v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <about></about>
@endsection