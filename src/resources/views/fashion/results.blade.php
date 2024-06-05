@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>ファッションの組み合わせ結果</h1>
        @if(isset($combination))
            <p>{{ $combination }}</p>
        @else
            <p>No combination found.</p>
        @endif
        <a href="{{ url('/fashion/upload') }}" class="btn btn-primary">別の画像をアップロード</a>
    </div>
@endsection


