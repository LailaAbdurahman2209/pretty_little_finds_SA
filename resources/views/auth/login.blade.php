<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sign In - Pretty Little Finds SA</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-pink-50/60 font-sans antialiased text-gray-900">
        <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
            
            <!-- Left Side: Gorgeous Boutique Branding (Desktop Only) -->
            <div class="hidden lg:flex flex-col justify-between bg-rose-950 p-12 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-rose-950 via-pink-950 to-rose-900 opacity-95"></div>
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-pink-500 rounded-full filter blur-3xl opacity-25"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-rose-400 rounded-full filter blur-3xl opacity-20"></div>

                <div class="relative z-10">
                    <a href="/" class="text-xl font-bold tracking-widest uppercase text-pink-100">
                        Pretty Little Finds <span class="text-pink-400">SA</span>
                    </a>
                </div>

                <div class="relative z-10 max-w-md">
                    <span class="text-xs uppercase tracking-widest text-pink-300 font-semibold tracking-wider">Welcome Back, Gorgeous</span>
                    <h2 class="text-4xl font-extrabold tracking-tight mt-2 mb-4 text-white">Curated luxury, style, and beauty.</h2>
                    <p class="text-pink-200/80 text-sm leading-relaxed">Sign in to access your exclusive boutique wishlist, orders, and personalized shopping experience.</p>
                </div>

                <div class="relative z-10 text-xs text-pink-300/60">
                    &copy; {{ date('Y') }} Pretty Little Finds SA. All rights reserved.
                </div>
            </div>

            <!-- Right Side: Login Form (Fully Responsive for Mobile & Desktop) -->
            <div class="flex flex-col justify-center px-6 py-12 lg:px-16 xl:px-24 bg-pink-50/30 lg:bg-white">
                <div class="mx-auto w-full max-w-sm bg-white lg:bg-transparent p-8 lg:p-0 rounded-3xl lg:rounded-none shadow-xl lg:shadow-none border border-pink-100 lg:border-none">
                    
                    <!-- Mobile Logo Header -->
                    <div class="lg:hidden mb-8 text-center">
                        <a href="/" class="text-2xl font-black tracking-wider uppercase text-rose-950">
                            Pretty Little Finds <span class="text-rose-600">SA</span>
                        </a>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-bold tracking-tight text-rose-950">Sign in to your account</h2>
                        <p class="text-sm text-gray-600 mt-1">Please enter your details below.</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 text-sm font-medium text-rose-600 bg-rose-50 p-3 rounded-xl border border-rose-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-rose-900 mb-1.5">Email Address</label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                class="w-full px-4 py-3 bg-pink-50/30 border border-pink-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 focus:bg-white transition-all shadow-sm"
                                placeholder="you@example.com"
                            >
                            @error('email')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-rose-900">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs text-rose-600 hover:text-rose-800 font-semibold">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                class="w-full px-4 py-3 bg-pink-50/30 border border-pink-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 focus:bg-white transition-all shadow-sm"
                                placeholder="••••••••"
                            >
                            @error('password')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember" class="rounded border-pink-300 text-rose-600 shadow-sm focus:ring-rose-500 w-4 h-4">
                                <span class="ms-2.5 text-xs text-gray-600 font-medium">Remember me on this device</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full py-3.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                Sign In
                            </button>
                        </div>

                        <!-- Registration Link -->
                        @if (Route::has('register'))
                            <div class="text-center pt-4 border-t border-pink-100">
                                <p class="text-xs text-gray-600">
                                    Don't have an account yet? 
                                    <a href="{{ route('register') }}" class="text-rose-600 hover:text-rose-800 font-bold ml-1">Create account</a>
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </body>
</html>