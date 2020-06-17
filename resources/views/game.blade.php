@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/cards.css') }}" rel="stylesheet">
@endsection

@section('content')
    <game :initial-game="{{ json_encode($game) }}"></game>
@endsection
