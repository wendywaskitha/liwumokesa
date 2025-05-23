<x-guest-layout>
    <div class="p-8">
        <!-- Logo & Title -->
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo"
                 class="h-20 mx-auto mb-4"
                 onerror="this.src='https://ui-avatars.com/api/?name=M&color=7F9CF5&background=EBF4FF'">
            <h2 class="mb-2 text-3xl font-bold text-gray-900">Daftar Akun Baru</h2>
            <p class="text-gray-600">Silakan lengkapi data diri Anda</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700" />
                <div class="relative mt-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="bi bi-person"></i>
                    </span>
                    <x-text-input id="name"
                                 class="w-full pl-10 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                 type="text"
                                 name="name"
                                 :value="old('name')"
                                 required
                                 autofocus
                                 autocomplete="name"
                                 placeholder="Masukkan nama lengkap" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

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
                                 autocomplete="username"
                                 placeholder="nama@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div>
                <x-input-label for="phone_number" :value="__('Nomor Telepon')" class="text-gray-700" />
                <div class="relative mt-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="bi bi-phone"></i>
                    </span>
                    <x-text-input id="phone_number"
                                 class="w-full pl-10 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                 type="tel"
                                 name="phone_number"
                                 :value="old('phone_number')"
                                 required
                                 placeholder="08xxxxxxxxxx" />
                </div>
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Alamat')" class="text-gray-700" />
                <div class="relative mt-2">
                    <span class="absolute text-gray-500 top-3 left-3">
                        <i class="bi bi-geo-alt"></i>
                    </span>
                    <textarea id="address"
                              name="address"
                              class="w-full pl-10 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                              rows="3"
                              required
                              placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                </div>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
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

            <!-- Hidden Role Field -->
            <input type="hidden" name="role" value="wisatawan">

            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-indigo-600 transition-colors duration-200 hover:text-indigo-800"
                   href="{{ route('login') }}">
                    {{ __('Sudah punya akun?') }}
                </a>

                <x-primary-button class="px-6 py-3 ml-4 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800">
                    {{ __('Daftar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
