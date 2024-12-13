@extends('layouts.main')

@section('title', 'Головна')

@section('content')
    <div>
        <h1>Платформа для організації подій та заходів</h1>
    </div>

    <div>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endsection
