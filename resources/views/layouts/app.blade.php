<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .container {
            max-width: 1200px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800 rounded-lg p-2 hover:bg-gray-100 transition-colors duration-200">
                    HireSmart
                </a>
                <div class="flex items-center space-x-4">
                    <a href="" class="text-gray-700 hover:text-blue-600 font-medium rounded-lg p-2 hover:bg-gray-100 transition-colors duration-200">View Jobs</a>
                    @auth
                        @if (Auth::user()->role === 'employer')
                            <a href="" class="text-gray-700 hover:text-blue-600 font-medium rounded-lg p-2 hover:bg-gray-100 transition-colors duration-200">My Jobs</a>
                            <a href="" class="text-gray-700 hover:text-blue-600 font-medium rounded-lg p-2 hover:bg-gray-100 transition-colors duration-200">Post New Job</a>
                        @elseif (Auth::user()->role === 'admin')
                            <a href="" class="text-gray-700 hover:text-blue-600 font-medium rounded-lg p-2 hover:bg-gray-100 transition-colors duration-200">Admin Metrics</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{route('login')}}" class="text-gray-700 hover:text-blue-600 font-medium rounded-lg p-2 hover:bg-gray-100 transition-colors duration-200">Log In</a>
                        <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
