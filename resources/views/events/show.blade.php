@extends('layouts.main')
@section('title',$event['name'])
@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card" style="width: 40rem;">
                <img src="https://storage.concert.ua/JTU/8/De/6704e41c6a22e/a230.jpg:31-catalog-event_item-desktop2x" class="card-img-top" alt="...">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Категорія: {{ $event->category->name }}</h6>
                    <h5 class="card-title">{{ $event->name }} </h5>
                    <!-- <h5 class="card-subtitle mb-2 text-muted">{{\Carbon\Carbon::parse($event->deadline)->format('d-m-Y H:i') }}</h5> -->
                    <h5 class="card-subtitle mb-2 text-muted">{{ $event->deadline->format('d-m-Y H:i')}}</h5>
                    <h6 class="card-subtitle mb-2">{{ $event->venue }}</h6>
                    <p class="card-text">{{ $event->description }}</p>
                    <div>
                        <h6 class="card-subtitle mb-2">Вартість квитків:</h6>
                        <ul>
                            @foreach ($event->costs as $cost)
                                <li>
                                {{ $cost->name }} 
                                    @if ($cost->price != 0)
                                        - Вартість: {{ $cost->price }} грн.
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('events.edit',[$event]) }}" class="btn btn-sm btn-outline-primary">Редагувати</a>
                        <!-- При натисканні кнопки "Видалити" перенаправляти користувача на проміжну сторінку
                        на цій сторінці користувач підтверджує або скасовує видалення. -->
                        <form action="{{ route('events.confirm_delete', ['event' => $event->id]) }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning">Видалити</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
@endsection