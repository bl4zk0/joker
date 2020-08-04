@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/style2.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="wrapper">
        <div class="cover-container p-3 mx-auto">
            <header class="masthead">
                @include('layouts.newnav')
            </header>

            <main>
                <div class="card text-dark" style="width:42em">
                    <div class="card-header"><i class="fas fa-envelope"></i> მოგვწერეთ</div>

                    <div class="card-body">
                        <form method="POST" action="/contact">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">სახელი</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           minlength="3"
                                           required @if(Auth::check()) value="{{ Auth::user()->username }}" @endif>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">ელ-ფოსტა</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           required @if(Auth::check()) value="{{ Auth::user()->email }}" @endif>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message">წერილი</label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message"
                                          rows="5"
                                          name="message"
                                          required minlength="10"></textarea>
                                @error('message')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button class="btn btn-success" type="submit">გაგზავნა</button>

                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
