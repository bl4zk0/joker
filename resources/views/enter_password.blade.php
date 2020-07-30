@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
                <div class="card mt-5">
                    <div class="card-body">
                        <form method="GET">
                            @csrf

                            <div class="form-row">
                                <div>
                                    <input type="text"
                                           maxlength="4"
                                           placeholder="პაროლი"
                                           class="form-control @error('p') is-invalid @enderror" name="p" required >

                                    @error('p')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="ml-2">
                                    <button class="btn btn-success">შესვლა</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
@endsection
