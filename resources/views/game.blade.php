@extends('layouts.app')

@section('content')
    <game :initial-game="{{ json_encode($game) }}"></game>
@endsection
