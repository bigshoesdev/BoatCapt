@extends('templates.app')

@section('title', 'Admin Revenue')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-revenue></admin-revenue>
@endsection