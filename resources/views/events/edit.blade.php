@extends('layouts.main')
@section('title','Редагування події')
@section('content')
    <h2>Редагувати подію</h2>
    <div class="container">
    <form method="POST" action="{{ route('events.update', $event->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Назва</label>
            <div class="col-sm-10">
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror"
                    id="name" 
                    name="name"
                    placeholder="Введіть назву"
                    value="{{ old('name',$event->name) }}"
                >
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="category_id" class="col-sm-2 col-form-label">Категорія</label>
            <div class="col-sm-10">
                <select 
                    class="form-select  @error('category_id') is-invalid @enderror" 
                    aria-label="Default select example" 
                    id="category_id" 
                    name="category_id"
                >
                    @foreach($categories as $category)
                    <option 
                        value="{{ $category->id }}"
                        {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="deadline" class="col-sm-2 col-form-label">Дата та час</label>
            <div class="col-sm-10">
                <input 
                    type="datetime-local" 
                    class="form-control @error('deadline') is-invalid @enderror" 
                    id="deadline" 
                    name="deadline"
                    value="{{ old('deadline', $event->deadline) }}"
                >
                @error('deadline')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="venue" class="col-sm-2 col-form-label">Місце проведення</label>
            <div class="col-sm-10">
                <input 
                    type="text" 
                    class="form-control @error('venue') is-invalid @enderror"
                    id="venue" 
                    name="venue"
                    placeholder="Адреса/Онлайн"
                    value="{{ old('venue', $event->venue) }}"
                >
                @error('venue')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Опис</label>
            <div class="col-sm-10">
                <textarea
                    class="form-control @error('description') is-invalid @enderror" 
                    id="description" 
                    name="description" 
                    placeholder="Введіть опис">{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="costs" class="col-sm-2 col-form-label">Типи квитків (за вартістю)</label>
            <div class="col-sm-10">
                <select 
                    class="form-select @error('costs') is-invalid @enderror"  
                    id="costs" 
                    multiple name="costs[]"

                >
                @foreach ($costs as $cost)
                    <option 
                        value="{{ $cost->id }}" 
                        @if (in_array($cost->id, old('costs', $event->costs->pluck('id')->toArray()))) 
                            selected 
                        @endif
                    >
                        {{ $cost->name }} 
                        @if ($cost->price != 0)
                            - Вартість: {{ $cost->price }} грн.
                        @endif
                    </option>
                @endforeach
                </select>
                @error('costs')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>     
        <div class="row mb-3">
            <label for="image" class="col-sm-2 col-form-label">Зображення</label>
            <div class="col-sm-10">
                <!-- Field for uploading an image file -->
                <input 
                    class="form-control @error('image') is-invalid @enderror" 
                    type="file" 
                    id="image" 
                    name="image"
                >
                @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <!-- Hidden field to save previous file path -->
                <input 
                    type="hidden" 
                    name="previous_image" 
                    value="{{ old('previous_image', $event->image) }}"
                >
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Оновити</button>
    </form>
    </div>
@endsection
