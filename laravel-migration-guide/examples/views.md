# Exemplos de Views

## layouts/app.blade.php
```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - AgendeSaúde PDF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-100">
    @if(auth()->check())
        <div class="flex h-screen">
            @include('layouts.sidebar')
            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endif

    @stack('scripts')
</body>
</html>
```

## auth/login.blade.php
```php
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-md shadow-lg w-96 max-w-full">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Prefeitura" class="h-16 object-contain">
        </div>

        <div class="text-center text-gray-700 text-sm mb-6">
            Prefeitura Municipal de Pau dos Ferros
        </div>

        <h2 class="text-xl font-semibold mb-8 text-center text-gray-800">
            <i class="fas fa-calendar-check text-blue-600 mr-2"></i>AgendeSaúde PDF
        </h2>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-sm mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="username">Usuário</label>
                <input class="appearance-none border border-gray-300 rounded-md w-full py-2.5 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Digite seu usuário">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="password">Senha</label>
                <input class="appearance-none border border-gray-300 rounded-md w-full py-2.5 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    id="password" type="password" name="password" placeholder="Digite sua senha">
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-md w-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" type="submit">
                    Entrar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
```

## calendar/index.blade.php
```php
@extends('layouts.app')

@section('title', 'Calendário')

@section('content')
<div class="p-4">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <button id="hoje" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                Hoje
            </button>
            <div class="flex items-center space-x-2">
                <button id="prevMonth" class="p-2 rounded-full hover:bg-gray-100">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="nextMonth" class="p-2 rounded-full hover:bg-gray-100">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <h2 id="monthYearDisplay" class="text-xl font-normal text-gray-800"></h2>
        </div>
    </div>

    <div class="border rounded-lg shadow-sm bg-white overflow-hidden">
        <table class="w-full border-collapse calendar">
            <thead>
                <tr>
                    <th class="p-2 border-b text-left text-xs font-medium text-gray-500 w-1/7">DOM</th>
                    <th class="p-2 border-b text-left text-xs font-medium text-gray-500 w-1/7">SEG</th>
                    <th class="p-2 border-b text-left text-xs font-medium text-gray-500 w-1/7">TER</th>
                    <th class="p-2 border-b text-left text-xs font-medium text-gray-500 w-1/7">QUA</th>
                    <th class="p-2 border-b text-left text-xs font-medium text-gray-500 w-1/7">QUI</th>
                    <th class="p-2 border-b text-left text-xs font-medium text-gray-500 w-1/7">SEX</th>
                    <th class="p-2 border-b text-left text-xs font-medium text-gray-500 w-1/7">SÁB</th>
                </tr>
            </thead>
            <tbody id="calendar-body">
                <!-- Calendário será preenchido via JavaScript -->
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/calendar.js') }}"></script>
@endpush
``` 