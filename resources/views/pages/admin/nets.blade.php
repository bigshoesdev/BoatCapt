@extends('templates.app')

@section('title', 'Admin Net')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-nets></admin-nets>
@endsection