@extends('layouts.app')

@section('nav')
@include('layouts.nav')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card">
                <div class="card-header fw-bold"><i class="fas fa-at"></i> @lang('Change email')</div>

                <div class="card-body">
                    <form method="POST" action="/email/change">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">@lang('Email')</label>

                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required>

                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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