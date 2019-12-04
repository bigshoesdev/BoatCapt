@extends('templates.app')

@section('title', 'Send Request Captain')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <owner-request-captain v-bind:captain-info="{{ $captainInfo }}"></owner-request-captain>
@endsection