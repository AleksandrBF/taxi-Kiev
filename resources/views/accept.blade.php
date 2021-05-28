@extends('layouts.layout')

@section('content')
    <section class="jumbotron text-center mt-5">
        <div class="container">
            @if(empty($data))
                <h1>Заказ пуст !!!</h1>
                <p>
                    <a href="{{ route('main') }}" class="btn btn-primary my-2">На главную</a>
                </p>
            @else
                <h1>{{ $title }}</h1>
                <p class="lead text-muted">
                    Спасибо вашь заказ принят. Время - {{ $data->time }}. Поездка от "{{ $data->from }}" до "{{ $data->to }}", растояние - {{ $data->distance_km }} км ({{ $data->distance_time }}). Стоимость - {{ $data->price }}.
                </p>
                <p>
                    <a href="{{ route('main') }}" class="btn btn-primary my-2">На главную</a>
                </p>
            @endif

        </div>
    </section>
@endsection
