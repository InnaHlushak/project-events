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
                    <h5 class="card-subtitle mb-2 text-muted">{{ $event->deadline }}</h5>
                    <h6 class="card-subtitle mb-2">{{ $event->venue }}</h6>
                    <p class="card-text">{{ $event->description }}</p>
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-sm btn-outline-primary">Редагувати</a>
                        <a href="#" class="btn btn-sm btn-outline-warning">Видалити</a>
                    </div>
                </div>
            </div>
        </div>    
    </div>
@endsection