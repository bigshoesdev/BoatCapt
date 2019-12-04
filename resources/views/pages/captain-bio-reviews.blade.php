@extends('templates.app')

@section('title', 'Captain Bio Reviews')
@section('header')
	<page-header v-bind:dark="true" v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	<captain-bio-reviews v-bind:hire="{{ $hire }}" v-bind:captain-info="{{ $captainInfo }}" v-bind:reviews="{{ $reviews }}"></captain-bio-reviews>
@endsection