<x-guest-layout>
    <div class="p-8">
        <!-- Logo & Title -->
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo"
                 class="h-20 mx-auto mb-4"
                 onerror="this.src='https://ui-avatars.com/api/?name=M&color=7F9CF5&background=EBF4FF'">
            <h2 class="mb-2 text-3xl font-bold text-gray-900">Konfirmasi Password</h2>
            <p class="text-gray-600">
                Ini adalah area yang aman dari aplikasi. Mohon konfirmasi password Anda sebelum melanjutkan.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

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
                                 placeholder="Masukkan password Anda" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <x-primary-button class="justify-center w-full py-3 transition-all duration-200 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800">
                {{ __('Konfirmasi Password') }}
            </x-primary-button>

            <!-- Forgot Password Link -->
            <div class="text-center">
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-indigo-600 transition-colors duration-200 hover:text-indigo-800"
                       href="{{ route('password.request') }}">
                        {{ __('Lupa Password?') }}
                    </a>
                @endif
            </div>
        </form>

        <!-- Security Info -->
        <div class="mt-8 text-sm text-center text-gray-500">
            <p class="flex items-center justify-center gap-2">
                <i class="bi bi-shield-lock"></i>
                Area ini memerlukan verifikasi tambahan untuk keamanan
            </p>
        </div>
    </div>
</x-guest-layout>
