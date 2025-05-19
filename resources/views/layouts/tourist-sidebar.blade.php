// resources/views/layouts/tourist-sidebar.blade.php
<div class="h-full px-3 py-4 overflow-y-auto bg-white">
    <ul class="space-y-2">
        <li>
            <a href="{{ route('tourist.dashboard') }}"
               class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ request()->routeIs('tourist.dashboard') ? 'bg-gray-100' : '' }}">
                <svg class="w-6 h-6 text-gray-500 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="ml-3">Dashboard</span>
            </a>
        </li>
        <!-- Tambahkan menu lainnya -->
    </ul>
</div>
