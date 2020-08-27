@extends('layouts.app')

@section('content')
    <div class="lobby-wrapper">
        <div class="container">
            @include('layouts.nav')

            <div class="card mt-3">
                <div class="card-header">
                    <strong>მაგიდები</strong>
                    <button type="button"
                            class="btn btn-primary ml-auto"
                            data-toggle="modal"
                            data-target="#staticBackdrop" style="float: right">
                        <i class="fas fa-plus-circle"></i> ახალი მაგიდა
                    </button>
                </div>
                <lobby :initial-games="{{ json_encode($games)  }}"></lobby>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">ახალი მაგიდა</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/games" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="type">ტიპი</label>
                            <select id="type" class="form-control" name="type">
                                <option value="1" selected>სტანდარტული</option>
                                <option value="9">9-იანები</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="penalty">ხიშტი</label>
                            <select id="penalty" class="form-control" name="penalty">
                                <option value="-200" selected>-200</option>
                                <option value="-300">-300</option>
                                <option value="-400">-400</option>
                                <option value="-500">-500</option>
                                <option value="-900">-900</option>
                                <option value="-1000">-1000</option>
                            </select>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="pwd" name="password">
                            <label class="form-check-label" for="pwd">პინ-კოდი</label>
                        </div>
                        <button class="btn btn-primary btn-block" onclick="Echo.leave('lobby')">მაგიდის შექმნა</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
