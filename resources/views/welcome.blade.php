<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="joker">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cover.css') }}" rel="stylesheet">

</head>
<body class="text-center bg-success">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="masthead">
        <div class="inner">
            <h3 class="masthead-brand">mojokre.dev</h3>
            <nav class="nav nav-masthead justify-content-center">
                <a class="nav-link active" href="#">მთავარი</a>
                <a class="nav-link" href="#">კავშირი</a>
            </nav>
        </div>
    </header>

    <main role="main" class="inner cover">
        <h1 class="cover-heading"><img src="{{ asset('storage/imgs/logo.png') }}"></h1>
        <p class="lead">კეთილი იყოს თქვენი მობრძანება ჯოკერის პორტალზე! ითამაშეთ და გაერთეთ 🙂</p>
        <p class="lead">
            <a href="/lobby" class="btn btn-lg btn-success">თამაშის დაწყება</a>
        </p>
    </main>
</div>
</body>
</html>
