@extends('layouts.main')
@section('title','Перелік подій')
@section('content')
    <h2>Перeлік подій та заходів</h2>
        @empty($events)
            <p>Події відсутні</p>
        @endempty
        <div class="row row-cols-3">
            @foreach ($events as $event)
                <div class="container">
                    <div class="d-flex justify-content-around">
                        <div class="card" style="width: 20rem;">
                            <!-- <img src="https://storage.concert.ua/JTU/8/De/6704e41c6a22e/a230.jpg:31-catalog-event_item-desktop2x" class="card-img-top" alt="..."> -->
                            <img src="{{ asset("storage/$event->image") }}" class="card-img-top" alt="Афіша">

                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Категорія: {{ $event->category->name }}</h6>
                                <h5 class="card-title">{{ $event->name }} </h5>
                                <h5 class="card-subtitle mb-2 text-muted">{{\Carbon\Carbon::parse($event->deadline)->format('d-m-Y H:i') }}</h5>
                                <p>{{ $event->venue}}</p>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('events.show',[$event]) }}" class="btn btn-sm btn-outline-primary">Переглянути</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="container">
            <div  class="d-flex justify-content-center">
                <!-- Switching pages in the paginator -->
                {{ $events->links() }}
            </div> 
        </div>
    </div>
@endsection
