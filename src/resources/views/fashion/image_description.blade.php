@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>画像の説明</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <p>{{ $description }}</p>
        <a href="{{ url('/fashion/upload') }}" class="btn btn-primary">別の画像をアップロード</a>
    </div>
@endsection
