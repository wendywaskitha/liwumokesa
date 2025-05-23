<x-guest-layout>
    <div class="p-8">
        <!-- Logo & Title -->
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 mx-auto mb-4">
            <h2 class="mb-2 text-3xl font-bold text-gray-900">Selamat Datang!</h2>
            <p class="text-gray-600">Silakan masuk untuk melanjutkan</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
                <div class="relative mt-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <x-text-input id="email"
                                 class="w-full pl-10 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 required
                                 autofocus
                                 autocomplete="username"
                                 placeholder="nama@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
                <div class="relative mt-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="bi bi-lock"></i>
                    </span>
                    <x-text-input id="password"
                                 class="w-full pl-10 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                 type="password"
                                 name="password"
                                 required
                                 autocomplete="current-password"
                                 placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me"
                           type="checkbox"
                           class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
                           name="remember">
                    <span class="text-sm text-gray-600 ms-2">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 transition-colors duration-200 hover:text-indigo-800"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <x-primary-button class="justify-center w-full py-3 transition-all duration-200 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800">
                {{ __('Log in') }}
            </x-primary-button>

            <!-- Register Link -->
            <p class="text-sm text-center text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 transition-colors duration-200 hover:text-indigo-800">
                    Daftar Sekarang
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
