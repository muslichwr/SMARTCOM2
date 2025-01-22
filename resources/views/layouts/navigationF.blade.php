<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <x-application-logo class="block h-10 w-auto" />
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            @auth
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center gap-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">{{ Auth::user()->name }} <x-bi-chevron-down /></button>
                <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
            <button data-collapse-toggle="navbar-sticky" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <x-heroicon-o-bars-3 />
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul
                class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                <li>
                    <a href="/"
                        class="{{ request()->is('/') ? 'text-white md:text-blue-700 bg-blue-700 md:dark:text-blue-500' : 'text-gray-900' }} md:bg-transparent block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                        aria-current="page">Home</a>
                </li>
                @auth
                    <li>
                        <a href="/user/pretest"
                            class="{{ request()->is('user/pretest*') ? 'text-white md:text-blue-700 bg-blue-700 md:dark:text-blue-500' : 'text-gray-900' }} md:bg-transparent block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Pre
                            Test</a>
                    </li>
                @endauth
                <li>
                    <a href="/user/materi"
                        class="{{ request()->is('user/materi*') ? 'text-white md:text-blue-700 bg-blue-700 md:dark:text-blue-500' : 'text-gray-900' }} md:bg-transparent block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Materi</a>
                </li>
                @auth
                    @php
                        $user = Auth::user();
                        $preTestAttempt = \App\Models\PreTestAttempt::where('user_id', $user->id)->first();
                        $preTestStatus = $preTestAttempt ? $preTestAttempt->status : null;
                    @endphp

                    @if ($preTestStatus == 1 || $preTestStatus == 2)
                        <li>
                            <a href="/user/postest"
                                class="{{ request()->is('user/postest*') ? 'text-white md:text-blue-700 bg-blue-700 md:dark:text-blue-500' : 'text-gray-900' }} md:bg-transparent block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Post
                                Test</a>
                        </li>
                    @endif
                @endauth
                <li>
                    <a href="/tentang"
                        class="{{ request()->is('tentang') ? 'text-white md:text-blue-700 bg-blue-700 md:dark:text-blue-500' : 'text-gray-900' }} md:bg-transparent block py-2 px-3 rounded md:p-0 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                        aria-current="page">Tentang</a>
                </li>
                <li>
                    <a href="{{ route('bantuan.index') }}"
                        class="{{ request()->is('bantuan') ? 'text-white md:text-blue-700 bg-blue-700 md:dark:text-blue-500' : 'text-gray-900' }} md:bg-transparent block py-2 px-3 rounded md:p-0 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                        aria-current="page">Bantuan</a>
                </li>
                @if(Auth::check() && (Auth::user()->role_as == 1 || Auth::user()->role_as == 2))
                    <!-- Menampilkan link untuk admin dan guru yang akan mengarah ke dashboard -->
                    <li>
                        <a href="{{ url('/admin/dashboard') }}"
                            class="{{ request()->is('admin/dashboard') ? 'text-white md:text-blue-700 bg-blue-700 md:dark:text-blue-500' : 'text-gray-900' }} md:bg-transparent block py-2 px-3 rounded md:p-0 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                            aria-current="page">
                            Dashboard
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
