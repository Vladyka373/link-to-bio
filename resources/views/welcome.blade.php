<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-body">
        <header class="app-header">
            <div class="header-container">
                @if (Route::has('login'))
                    <nav class="nav-container">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="nav-button nav-button-outlined">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav-button nav-button-outlined">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="nav-button nav-button-outlined">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="app-main">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <div class="mb-10">
                    <h1 class="welcome-title text-4xl md:text-5xl font-bold mb-6">
                        Create Your Perfect Link-in-Bio Page
                    </h1>
                    <p class="welcome-subtitle text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto">
                        Link-To-Bio helps you share multiple links in one place. Perfect for social media, influencers, and businesses.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="inline-block px-8 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-300">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-block px-8 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-300">
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="inline-block px-8 py-3 bg-white text-indigo-600 font-medium rounded-lg shadow-md border border-indigo-200 hover:bg-indigo-50 transition-colors duration-300 dark:bg-gray-800 dark:text-indigo-400 dark:border-gray-700 dark:hover:bg-gray-700">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>

                <div class="mt-16 bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-3xl mx-auto border border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold mb-4 dark:text-white">How It Works</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4">
                            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-indigo-600 dark:text-indigo-400 font-bold">1</span>
                            </div>
                            <h3 class="font-medium mb-2 dark:text-white">Sign Up</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Create your account in seconds</p>
                        </div>
                        <div class="p-4">
                            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-indigo-600 dark:text-indigo-400 font-bold">2</span>
                            </div>
                            <h3 class="font-medium mb-2 dark:text-white">Add Links</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Include all your important links</p>
                        </div>
                        <div class="p-4">
                            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-indigo-600 dark:text-indigo-400 font-bold">3</span>
                            </div>
                            <h3 class="font-medium mb-2 dark:text-white">Share</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Share your unique link everywhere</p>
                        </div>
                    </div>
                </div>

                <div class="mt-12">
                    <h3 class="text-xl font-semibold mb-4 dark:text-white">Ready to get started?</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Join thousands of users who have created their perfect link-in-bio page</p>
                </div>
            </div>
        </main>
    </body>
</html>