@extends('layouts.layout')

@section('content')
    <h3>Онлайн такси Киев</h3>

    <div class="row">

        <div class="col">
            <div id="map"></div>
        </div>

        <div class="modal-content modal-form">
            <div class="modal-body">
                <form method="post" action="{{ route('order_ok') }}">
                    @csrf
                    <div class="form-group">
                        <label for="from">Откуда</label>
                        <input type="text" class="form-control" placeholder="Выбирите адресс" id="from" name="from" required>
                    </div>
                    <div class="form-group">
                        <label for="to">Куда</label>
                        <input type="text" class="form-control" placeholder="Выбирите адресс" id="to" name="to" required>
                    </div>
                    <div class="form-group">
                        <label for="comment">Коментарий для водителя</label>
                        <textarea cols="3" class="form-control" id="comment" name="comment"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="time">Время</label>
                        <input class="form-control timepicker" id="time" name="time">
                        <span class="help-line"></span>
                    </div>
                    <input type="hidden" name="distance" value="">
                    <input type="hidden" name="distance_time" value="">
                    <div class="row mb-3">
                        <div class="col" id="price_sum"></div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-block btn-success">Заказать</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('template_js')
    @include('js/main_js')
@endsection
