@extends('templates.app')

@section('title', 'Create Bid')
@section('header')
	<page-header v-bind:param="{{ $param }}"></page-header>
@endsection
@section('content')
    @if($errors->any())
    <create-bid v-bind:message="{{ json_encode(['status' => 'failed', 'body' => $errors->all()]) }}" v-bind:bid-request="{{ $bidRequest }}" v-bind:old-input="{{ json_encode(['chargeType' => old('chargeType'), 'hours' => old('hours'), 'amount' => old('amount'), 'describe' => old('describe')]) }}"></create-bid>
    @else
    <create-bid v-bind:bid-request="{{ $bidRequest }}" v-bind:old-input="{{ json_encode(['chargeType' => '', 'hours' => 1, 'amount' => '', 'describe' => '']) }}"></create-bid>
    @endif
@endsection