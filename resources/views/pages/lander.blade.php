@extends('templates.app')

@section('title', 'Lander')
@section('header')
	<page-header v-bind:dark="true" v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <lander v-bind:info="{{ $info }}"></lander>    
@endsection