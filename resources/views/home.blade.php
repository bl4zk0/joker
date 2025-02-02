@extends('layouts.app')

@section('nav')
    @include('layouts.nav')
@endsection

@section('content')
    <div class="container-sm">
        <div class="p-3">
            <main role="main" class="text-center">
                <h1><img src="{{ asset('storage/imgs/logo.png') }}" alt="logo"></h1>
                <p>@lang('Welcome on Joker portal! Game on and GLHF!')</p>
                <p>
                    <a href="{{ route('lobby') }}" class="btn btn-lg border">
                        @lang('Play Now')
                    </a>
                </p>
            </main>
        </div>
    </div>
@endsection
