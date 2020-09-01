@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/cards.css') }}" rel="stylesheet">
@endsection

@section('content')
    <game-view :game-id="{{ json_encode($id) }}"
               :has-password="{{ json_encode($password) }}"
               :pin-code="{{ json_encode($pin) }}"></game-view>

    @if(Auth::user()->isAdmin)
        <admin-panel :game-id="{{ $id }}"></admin-panel>
    @endif
@endsection
