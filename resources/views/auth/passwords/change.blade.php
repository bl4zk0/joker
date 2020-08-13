@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mt-3">
                    <div class="card-header"><i class="fas fa-key"></i> პაროლის შეცვლა</div>

                    <div class="card-body">
                        <form method="POST" action="/password/change">
                            @csrf

                            <div class="form-group">
                                <label for="password">ახალი პაროლი</label>

                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="new-password">
                                <small class="form-text text-muted">მინიმუმ 8 სიმბოლო</small>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">დაადასტურეთ პაროლი</label>

                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-block btn-success">
                                    შეცვლა
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
