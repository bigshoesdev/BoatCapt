@extends('templates.app')

@section('title', 'Owner Leave Review')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    @if($message = session('status'))
	<owner-leave-review v-bind:message="{{ json_encode(['status' => 'success', 'body' => [$message]]) }}" v-bind:trip-info="{{ $tripInfo }}" v-bind:review-info="{{ $reviewInfo }}"></owner-leave-review>
    @elseif($errors->any())
    <owner-leave-review v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:trip-info="{{ $tripInfo }}" v-bind:review-info="{{ $reviewInfo }}"></owner-leave-review>
    @else
    <owner-leave-review v-bind:trip-info="{{ $tripInfo }}" v-bind:review-info="{{ $reviewInfo }}"></owner-leave-review>
    @endif
@endsection