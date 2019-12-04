@extends('templates.app')

@section('title', 'Captain Bid Submitted')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <bid-submitted></bid-submitted>
@endsection