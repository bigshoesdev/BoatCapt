@extends('templates.app')

@section('title', 'Book Captain')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($errors->any())
    <owner-book-captain v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:captain-info="{{ $captainInfo }}"></owner-booking>
    @else
    <owner-book-captain v-bind:captain-info="{{ $captainInfo }}" v-bind:is-admin="{{ isset($isAdmin)?$isAdmin:0 }}"></owner-book-captain>
    @endif
@endsection