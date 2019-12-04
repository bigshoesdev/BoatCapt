@extends('templates.app')

@section('title', 'Captain Leave Review')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
	@if($message = session('status'))
	<captain-leave-review v-bind:message="{{ json_encode(['status' => 'success', 'body' => [$message]]) }}" v-bind:trip-info="{{ $tripInfo }}" v-bind:review-info="{{ $reviewInfo }}"></captain-leave-review>
    @elseif($errors->any())
    <captain-leave-review v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:trip-info="{{ $tripInfo }}" v-bind:review-info="{{ $reviewInfo }}"></captain-leave-review>
    @else
    <captain-leave-review v-bind:trip-info="{{ $tripInfo }}" v-bind:review-info="{{ $reviewInfo }}"></captain-leave-review>
    @endif
@endsection