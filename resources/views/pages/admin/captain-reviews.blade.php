@extends('templates.app')

@section('title', 'Captain Reviews')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-captain-reviews v-bind:user-info="{{ $userInfo }}"></admin-captain-reviews>
@endsection