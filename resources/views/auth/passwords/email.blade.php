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
                            <i class="fa-solid fa-unlock-keyhole"></i> @lang('Reset password')
                        </h1>
                    </div>
                </div>

                <div class="card-body p-5 pt-0">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="@lang('Email')" required>
                            <label for="email">@lang('Email')</label>

                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-success" type="submit">
                            @lang('Send email')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection