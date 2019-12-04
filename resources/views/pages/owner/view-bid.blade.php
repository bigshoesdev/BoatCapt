@extends('templates.app')

@section('title', 'Owner View Bid')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <owner-view-bid v-bind:bid-info="{{ $bidInfo }}"></owner-view-bid>
@endsection