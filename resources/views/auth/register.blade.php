@extends('layouts.app')

@section('nav')
@include('layouts.nav')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card rounded-4">

                <div class="card-body p-5 pb-4">
                    <div class="card-title">
                        <h1 class="fw-bold mb-0 fs-4">
                            <i class="fa-solid fa-user-plus"></i> @lang('Sign up') @lang('to Joker')
                        </h1>
                    </div>
                </div>

                <div class="card-body p-5 pt-0">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input id="username" type="text" class="form-control rounded-3 @error('username') is-invalid 
                                @enderror" name="username" placeholder="@lang('Username')" 
                                value="{{ old('username') }}" required autofocus>
                            <label for="username">@lang('Username')</label>

                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="@lang('Email')" required>
                            <label for="email">@lang('Email')</label>

                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" placeholder="@lang('Password')"
                                class="form-control @error('password') is-invalid @enderror" name="password" required>
                            <label for="password">@lang('Password')</label>
                            <small class="form-text">@lang('Min 8 characters')</small>

                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" placeholder="@lang('Confirm password')" required>
                            <label for="password-confirm">@lang('Confirm password')</label>
                        </div>

                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-success" type="submit">
                            @lang('Sign up')
                        </button>

                        <div class="text-center">
                            <a class="btn btn-link" href="{{ route('login') }}">
                                <small>@lang('Already registered?')</small>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection