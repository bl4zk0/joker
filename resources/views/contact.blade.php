@extends('layouts.app')

@section('nav')
@include('layouts.nav')
@endsection

@section('content')
<div class="container">
    <div class="card mt-3">
        <div class="card-header"><i class="fas fa-envelope"></i> @lang('Contact us')</div>

        <div class="card-body">
            <form method="POST" action="/contact">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">@lang('Name')</label>
                        <input type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            minlength="3"
                            required @if(Auth::check()) value="{{ Auth::user()->username }}" @endif>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">@lang('Email')</label>
                        <input type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            required @if(Auth::check()) value="{{ Auth::user()->email }}" @endif>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">@lang('Message')</label>
                    <textarea class="form-control @error('message') is-invalid @enderror"
                        id="message"
                        rows="5"
                        name="message"
                        required minlength="10"></textarea>
                    @error('message')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button class="btn btn-success float-end" type="submit">@lang('Send')</button>
            </form>
        </div>
    </div>
</div>
@if (session('status'))
<flash :message="{{ json_encode(session('status')) }}"></flash>
@endif
@endsection