@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mt-3">
                    <div class="card-header"><i class="fas fa-sign-in-alt"></i> 
                    <b>@lang('Sign in') @lang('to Joker')</b>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="username">@lang('Username')</label>

                                <input id="username" type="text"
                                       class="form-control @error('username') is-invalid @enderror" name="username"
                                       value="{{ old('username') }}" required autofocus>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">@lang('Password')</label>

                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember"
                                           id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        @lang('Remember me')
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-success">
                                    @lang('Sign in')
                                </button>
                            </div>
                        </form>
                        <div class="mb-3">
                            <a href="/login/facebook" class="btn btn-block btn-outline-primary"><i
                                    class="fab fa-facebook-f"></i> @lang('Sign in with Facebook')</a>
                        </div>
                        <div class="mb-3">
                            <a href="/login/google" class="btn btn-block btn-outline-danger"><i
                                    class="fab fa-google"></i> @lang('Sign in with Google')</a>
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
