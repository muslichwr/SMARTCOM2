<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="/admin/dashboard"
                    class="{{ request()->is('admin/dashboard') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-s-home class="w-7" />
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li class="pt-4 uppercase text-gray-500 text-xs font-semibold px-3">-- CRUD --</li>
            <li>
                <a href="/admin/materi"
                    class="{{ request()->is('admin/materi*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-inbox class="w-7" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Materi</span>
                </a>
            </li>
            <li>
                <a href="/admin/bab"
                    class="{{ request()->is('admin/bab*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-inbox-stack class="w-7" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Bab</span>
                </a>
            </li>
            <li>
                <a href="/admin/soal-jawaban"
                    class="{{ request()->is('admin/soal-jawaban*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-clipboard-document-list class="w-7" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Soal</span>
                </a>
            </li>
            <li>
                <a href="/admin/latihan"
                    class="{{ request()->is('admin/latihan*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Latihan</span>
                </a>
            </li>
            <li>
                <a href="/admin/PrePost"
                    class="{{ request()->is('admin/PrePost') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-pencil class="w-7" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Pre & Post</span>
                </a>
            </li>
            <li>
                <a href="/admin/users"
                    class="{{ request()->is('admin/users') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-users class="w-7" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Akun</span>
                </a>
            </li>
            <li class="pt-4 uppercase text-gray-500 text-xs font-semibold px-3">-- Management Pemilihan Soal --</li>
            <li>
                <a href="/admin/PrePostTest"
                    class="{{ request()->is('admin/PrePostTest*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-academic-cap class="w-6 h-6 text-current" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Pre & Post Test</span>
                </a>
            </li>
            <li>
                <a href="/admin/test"
                    class="{{ request()->is('admin/test*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-pencil-square class="w-7" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Test</span>
                </a>
            </li>
            <li class="pt-4 uppercase text-gray-500 text-xs font-semibold px-3">-- Project Based Learning --</li>
            <li>
                <a href="/admin/pjbl/kelompok"
                class="{{ request()->is('admin/pjbl/kelompok*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                <x-heroicon-o-pencil-square class="w-7" />
                <span class="flex-1 ms-3 whitespace-nowrap">PJBL (Kelompok)</span>
            </a>
            </li>
            {{-- <li>
                <a href="/admin/proyek/anggotaKelompok"
                    class="{{ request()->is('admin/proyek/anggotaKelompok*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-pencil-square class="w-7" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Kelompok (Anggota)</span>
                </a>
            </li> --}}
            <li class="pt-4 uppercase text-gray-500 text-xs font-semibold px-3">-- Lainnya --</li>
            <li>
                <a href="/admin/riwayat"
                    class="{{ request()->is('admin/riwayat*') ? 'bg-blue-700 text-white' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-100 hover:text-gray-900 dark:hover:bg-gray-700 group">
                    <x-heroicon-o-clock class="w-7" />
                    <span class="flex-1 ms-3 whitespace-nowrap">Riwayat</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
