<x-guest-layout>
    <div class="p-8">
        <!-- Logo & Title -->
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo"
                 class="h-20 mx-auto mb-4"
                 onerror="this.src='https://ui-avatars.com/api/?name=M&color=7F9CF5&background=EBF4FF'">
            <h2 class="mb-2 text-3xl font-bold text-gray-900">Lupa Password?</h2>
            <p class="text-gray-600">Masukkan email Anda untuk menerima link reset password</p>
        </div>

        <!-- Session Status -->
        <div class="mb-6">
            @if (session('status'))
                <div class="p-4 text-sm text-green-600 rounded-lg bg-green-50">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                                 placeholder="nama@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <x-primary-button class="justify-center w-full py-3 transition-all duration-200 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800">
                {{ __('Kirim Link Reset Password') }}
            </x-primary-button>

            <!-- Back to Login -->
            <p class="text-sm text-center text-gray-600">
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 transition-colors duration-200 hover:text-indigo-800">
                    <i class="bi bi-arrow-left me-1"></i>
                    {{ __('Kembali ke halaman login') }}
                </a>
            </p>
        </form>

        <!-- Additional Info -->
        <div class="mt-8 text-sm text-center text-gray-500">
            <p>Belum menerima email?</p>
            <p>Periksa folder spam atau tunggu beberapa menit</p>
        </div>
    </div>
</x-guest-layout>
