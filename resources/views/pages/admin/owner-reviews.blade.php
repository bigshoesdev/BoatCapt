@extends('templates.app')

@section('title', 'Owner Reviews')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <admin-owner-reviews v-bind:user-info="{{ $userInfo }}"></admin-owner-reviews>
@endsection