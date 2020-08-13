@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card mt-3">
                <div class="card-header"><i class="fas fa-lock"></i> {{ __('Confirm Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                                <button type="submit" class="btn btn-block btn-success">
                                    გაგრძელება
                                </button>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                <small>{{ __('Forgot Your Password?') }}</small>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
