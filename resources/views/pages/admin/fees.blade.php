@extends('templates.app')

@section('title', 'Admin Fees')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-fees></admin-fees>
@endsection