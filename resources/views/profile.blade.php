@extends('layouts.app')

@section('nav')
@include('layouts.nav')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="h-name">
                        <img src="{{ $user->avatar_url }}"
                            class="avatar border rounded-circle"
                            alt="avatar"> {{ $user->username }}
                    </h5>
                    @can('update', $user)
                    <form>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="fas fa-at"></i></div>
                            <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-6 d-grid mb-2">
                            <a href="/email/change" class="btn btn-success">
                                @lang('Change email')
                            </a>
                        </div>
                        <div class="col-sm-6 d-grid mb-2">
                            <a href="/password/change" class="btn btn-success">
                                @lang('Change password')
                            </a>
                        </div>
                        <small>@lang('Avatar powered by') <a href="https://gravatar.com" target="_blank">gravatar.com</a></small>
                    </div>
                    @endcan

                    <div>
                        <i class="fas fa-gamepad text-warning"></i> @lang('Games played'): <strong>{{ $user->player->games_played }}</strong>
                    </div>
                    <div>
                        <i class="fas fa-trophy text-success"></i> @lang('Games won'): <strong>{{ $user->player->games_won }}</strong>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@if (session('status'))
<flash :message="{{ json_encode(session('status')) }}"></flash>
@endif
@endsection