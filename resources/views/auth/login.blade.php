@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mt-3">
                    <div class="card-header"><i class="fas fa-sign-in-alt"></i> შესვლა</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="username">მომხმარებელი</label>

                                <input id="username" type="text"
                                       class="form-control @error('username') is-invalid @enderror" name="username"
                                       value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">პაროლი</label>

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
                                        დამიმახსოვრე
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-success">
                                    შესვლა
                                </button>
                            </div>
                        </form>
                        <div class="mb-3">
                            <a href="/login/facebook" class="btn btn-block btn-outline-primary"><i
                                    class="fab fa-facebook-f"></i> Facebook-ით შესვლა</a>
                        </div>
                        <div class="mb-3">
                            <a href="/login/google" class="btn btn-block btn-outline-danger"><i
                                    class="fab fa-google"></i> Google-ით შესვლა</a>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                <small>დაგავიწყდათ პაროლი?</small>
                            </a>
                            <a class="btn btn-link" href="{{ route('register') }}">
                                <small>რეგისტრაცია</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
