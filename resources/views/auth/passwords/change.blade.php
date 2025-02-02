@extends('layouts.app')

@section('nav')
@include('layouts.nav')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card">
                <div class="card-header fw-bold"><i class="fas fa-key"></i> @lang('Change password')</div>

                <div class="card-body">
                    <form method="POST" action="/password/change">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">@lang('Password')</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                required>
                            <small class="form-text">@lang('Min 8 characters')</small>

                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">@lang('Confirm password')</label>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-block btn-success float-end">
                            @lang('Confirm')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection