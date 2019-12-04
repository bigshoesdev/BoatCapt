@extends('templates.app')

@section('title', 'Admin Reviews')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-reviews></admin-reviews>
@endsection