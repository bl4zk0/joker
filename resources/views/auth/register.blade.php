@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mt-3">
                    <div class="card-header"><i class="fas fa-user"></i> 
                        <b>@lang('Sign up') @lang('to Joker')</b>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">@lang('Username')</label>

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
                                <label for="email">@lang('Email')</label>

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">@lang('Password')</label>

                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="new-password">
                                <small class="form-text text-muted">@lang('Min 8 characters')</small>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">@lang('Confirm password')</label>

                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-success">
                                    @lang('Sign up')
                                </button>
                            </div>
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
