<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div id="app">
        <nav class="bg-white shadow mb-8">
            <div class="container mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-lg font-semibold text-gray-900">
                        <a href="{{ url('/') }}" class="text-gray-900 no-underline">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    <div>
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-900 no-underline hover:text-gray-700">ログイン</a>
                            <a href="{{ route('register') }}" class="ml-4 text-gray-900 no-underline hover:text-gray-700">登録</a>
                        @else
                            <span class="text-gray-900">{{ Auth::user()->name }}</span>
                            <a href="{{ route('logout') }}"
                               class="ml-4 text-gray-900 no-underline hover:text-gray-700"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                ログアウト
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>

