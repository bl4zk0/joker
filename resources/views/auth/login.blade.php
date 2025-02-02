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
                            <i class="fas fa-sign-in-alt"></i>
                            @lang('Sign in') @lang('to Joker')
                        </h1>
                    </div>
                </div>

                <div class="card-body p-5 pt-0">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input id="username" type="text" class="form-control rounded-3 @error('username') is-invalid 
                                @enderror" name="username" placeholder="@lang('Username')" 
                                value="{{ old('username') }}" autofocus required>
                            <label for="username">@lang('Username')</label>

                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control rounded-3 @error('password') is-invalid 
                                @enderror" name="password" placeholder="@lang('Password')" required>
                            <label for="password">@lang('Password')</label>

                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check my-3">
                            <input class="form-check-input" type="checkbox" name="remember"
                                id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                @lang('Remember me')
                            </label>
                        </div>
                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-success" type="submit">
                            @lang('Sign in')
                        </button>
                    </form>
                    <hr>
                    <div>
                        <a href="/login/facebook" class="w-100 py-2 mb-2 btn btn-outline-primary rounded-3">
                            <i class="fab fa-facebook-f"></i> @lang('Sign in with Facebook')
                        </a>
                    </div>
                    <div>
                        <a href="/login/google" class="w-100 py-2 mb-2 btn btn-outline-danger rounded-3">
                            <i class="fab fa-google"></i> @lang('Sign in with Google')</a>
                        </a>
                    </div>

                    <div class="text-center">
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            <small>@lang('Forgot password?')</small>
                        </a>
                        <a class="btn btn-link" href="{{ route('register') }}">
                            <small>@lang('Sign up')</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection