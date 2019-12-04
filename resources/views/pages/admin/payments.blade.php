@extends('templates.app')

@section('title', 'Admin Payments')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-payments></admin-payments>
@endsection