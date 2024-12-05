@extends('layouts.main')

@section('content')
    <div class="container">
        <h3>Ви дійсно хочете видалити подію "{{ $event->name }}"?</h3>
        <form action="{{ route('events.destroy', ['event' => $event->id]) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">Так, видалити</button>
        </form>
        <a href="{{ route('events.show',[$event]) }}" class="btn btn-sm btn-outline-secondary">Скасувати</a>
    </div>
@endsection