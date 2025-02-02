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
                            <i class="fas fa-lock"></i> {{ __('Confirm password') }}
                        </h1>
                    </div>
                </div>

                <div class="card-body p-5 pt-0">
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="@lang('Password')" required>
                            <label for="password">{{ __('Password') }}</label>

                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-success" type="submit">
                            {{ __('Confirm') }}
                        </button>

                        <div class="text-center">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                <small>{{ __('Forgot password?') }}</small>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection