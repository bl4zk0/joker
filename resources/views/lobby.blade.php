@extends('layouts.app')

@section('content')
    <div class="container">

        <lobby :initial-games="{{ json_encode($games)  }}"></lobby>

    </div>
@endsection
