@extends('templates.app')

@section('title', 'Trip Detail')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    <owner-trip-detail v-bind:trip-info="{{ $tripInfo }}" v-bind:is-admin="{{ isset($isAdmin)?$isAdmin:0 }}"></owner-trip-detail>
@endsection