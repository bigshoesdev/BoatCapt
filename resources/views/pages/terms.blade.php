@extends('templates.app')

@section('title', 'Terms of Use')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <terms></terms>
@endsection