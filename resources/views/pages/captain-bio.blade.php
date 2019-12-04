@extends('templates.app')

@section('title', 'Captain Bio')
@section('header')
	<page-header v-bind:dark="true" v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <captain-bio v-bind:hire="{{ $hire }}" v-bind:captain-info="{{ $captainInfo }}"></captain-bio>
@endsection