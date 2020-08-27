@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="cover-container p-3 mx-auto">
            <header class="masthead">
                @include('layouts.nav')
            </header>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card text-dark">
                        <div class="card-body">
                            <div class="border-bottom pb-2">
                                <h5 class="h-name">
                                    <img src="{{ $user->avatar_url }}"
                                         class="avatar border rounded-circle"
                                         alt="avatar"> {{ $user->username }}
                                </h5>
                                @can('update', $user)
                                    <form>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-at"></i></div>
                                            </div>
                                            <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>
                                    </form>

                                    <a href="/email/change" class="btn btn-secondary btn-block my-2">ელ-ფოსტის შეცვლა</a>
                                    <a href="/password/change" class="btn btn-secondary btn-block my-2">პაროლის შეცვლა</a>

                                    <small class="mt-2">ავატარს უზრუნველყოფს <a href="https://gravatar.com" target="_blank">gravatar.com</a></small>
                                @endcan
                            </div>
                            <div class="mt-2">
                                <i class="fas fa-gamepad text-warning"></i> თამაშები: <strong>{{ $user->player->games_played }}</strong>
                            </div>
                            <div>
                                <i class="fas fa-trophy text-success"></i> მოგებული: <strong>{{ $user->player->games_won }}</strong>
                            </div>

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

