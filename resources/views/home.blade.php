@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="cover-container p-3 mx-auto">
            <header class="masthead">
                @include('layouts.nav')
            </header>

            <main role="main" class="inner cover text-center">
                <h1 class="cover-heading"><img src="{{ asset('storage/imgs/logo.png') }}" alt="logo"></h1>
                <p class="lead">@lang('Welcome on Joker portal! Game on and GLHF!')</p>
                <p class="lead">
                    <a href="{{ route('lobby') }}" class="btn btn-lg btn-success btn-main">@lang('Play Now')</a>
                </p>
            </main>
        </div>
    </div>
@endsection
