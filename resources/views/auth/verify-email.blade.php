<x-guest-layout>
    <div class="p-8">
        <!-- Logo & Title -->
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo"
                 class="h-20 mx-auto mb-4"
                 onerror="this.src='https://ui-avatars.com/api/?name=M&color=7F9CF5&background=EBF4FF'">
            <h2 class="mb-2 text-3xl font-bold text-gray-900">Verifikasi Email Anda</h2>
            <p class="text-gray-600">Silakan periksa email Anda untuk tautan verifikasi.</p>
        </div>

        <div class="p-8 shadow-xl bg-white/90 backdrop-blur-lg rounded-2xl">
            <p class="text-gray-700">
                Sebelum melanjutkan, silakan verifikasi email Anda dengan mengklik tautan yang telah kami kirimkan ke alamat email Anda.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="p-4 mt-4 text-green-600 rounded-lg bg-green-50">
                    Tautan verifikasi baru telah dikirim ke email Anda.
                </div>
            @endif

            <div class="flex items-center justify-between mt-6">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button>
                        {{ __('Kirim Ulang Tautan Verifikasi') }}
                    </x-primary-button>
                </form>

                <a class="text-sm text-indigo-600 transition-colors duration-200 hover:text-indigo-800"
                   href="{{ route('login') }}">
                    {{ __('Kembali ke halaman login') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
