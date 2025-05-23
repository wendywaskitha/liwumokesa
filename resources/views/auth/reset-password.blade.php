<x-guest-layout>
    <div class="p-8">
        <!-- Logo & Title -->
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo"
                 class="h-20 mx-auto mb-4"
                 onerror="this.src='https://ui-avatars.com/api/?name=M&color=7F9CF5&background=EBF4FF'">
            <h2 class="mb-2 text-3xl font-bold text-gray-900">Reset Password</h2>
            <p class="text-gray-600">Masukkan password baru Anda</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                                 :value="old('email', $request->email)"
                                 required
                                 autofocus
                                 autocomplete="username" />
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
                                 autocomplete="new-password"
                                 placeholder="Minimal 8 karakter" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700" />
                <div class="relative mt-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <x-text-input id="password_confirmation"
                                 class="w-full pl-10 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                 type="password"
                                 name="password_confirmation"
                                 required
                                 autocomplete="new-password"
                                 placeholder="Ulangi password" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <x-primary-button class="justify-center w-full py-3 transition-all duration-200 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800">
                {{ __('Reset Password') }}
            </x-primary-button>

            <!-- Back to Login -->
            <p class="text-sm text-center text-gray-600">
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 transition-colors duration-200 hover:text-indigo-800">
                    {{ __('Kembali ke halaman login') }}
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
