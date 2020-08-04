@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/style2.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="wrapper text-center">
        <div class="cover-container p-3 mx-auto">
            <header class="masthead">
                @include('layouts.newnav')
            </header>

            <main role="main" class="inner cover">
                <h1 class="cover-heading"><img src="{{ asset('storage/imgs/logo.png') }}"></h1>
                <p class="lead">კეთილი იყოს თქვენი მობრძანება ჯოკერის პორტალზე! ითამაშეთ და გაერთეთ 🙂</p>
                <p class="lead">
                    <a href="/lobby" class="btn btn-lg btn-success btn-main">თამაშის დაწყება</a>
                </p>
            </main>
        </div>
    </div>
@endsection
