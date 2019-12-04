@extends('templates.app')

@section('title', 'Captain Bid Request Detail')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <bid-request-detail v-bind:bid-request="{{ $bidRequest }}"></bid-request-detail>
@endsection