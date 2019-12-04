@extends('templates.app')

@section('title', 'Admin Owners')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-owners></admin-owners>
@endsection