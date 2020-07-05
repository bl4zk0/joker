@extends('layouts.app')

@section('nav')
    @include('layouts.nav')
@endsection

@section('content')
    <div class="container mt-4">

        <lobby :initial-games="{{ json_encode($games)  }}"></lobby>

    </div>
@endsection
