@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="cover-container p-3 mx-auto">
            <header class="masthead">
                @include('layouts.nav')
            </header>

            <main role="main" class="inner cover text-center">
                <h1 class="cover-heading"><img src="{{ asset('storage/imgs/logo.png') }}" alt="logo"></h1>
                <p class="lead">კეთილი იყოს თქვენი მობრძანება ჯოკერის პორტალზე! ითამაშეთ და გაერთეთ 🙂</p>
                <p class="lead">
                    <a href="{{ route('lobby') }}" class="btn btn-lg btn-success btn-main">თამაშის დაწყება</a>
                </p>
            </main>
        </div>
    </div>
@endsection
