<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="font-sans antialiased">
    <div class="bg-gray-100 flex dark:bg-gray-900">
        @include('layouts.navigation')

        <section class="w-full">
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white sticky top-0 z-99 dark:bg-gray-800 shadow w-full flex gap-2 px-3 justify-between">
                    <div class="py-6">
                        {{ $header }}
                    </div>

                    <div class="flex items-center">
                        {{-- clear cache --}}
                        <a href="{{ route('clear.cache') }}"
                            class="relative inline-flex size-max items-center gap-1 px-4 py-2 font-medium
                        text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500
                        focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100
                        active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600
                        dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300"><x-heroicon-s-arrow-path
                                class="size-[1.3em]"></x-heroicon-s-arrow-path>Refresh</a>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex cursor-pointer items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                <div class="px-3 py-3">
                    @if (session()->has('success'))
                        <x-toast-success :message="session('success')"></x-toast-success>
                    @elseif(session()->has('error'))
                        <x-toast-error :message="session('error')"></x-toast-error>
                    @endif
                    {{ $slot }}
            </main>
        </section>
    </div>

    {{-- Confirm Delete --}}
    <script defer>
        function confirmDelete(form) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Jika ini data yang memiliki relasi turunan ke tabel lain maka tabel turunan yang berelasi datanya akan ikut terhapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',

                // custom class ketika alert muncul
                // showClass: {
                //     popup: 'popup-show duration-1000',
                //     icon: 'swal2-icon-show duration-1000',
                // },

                // styling dengan class tailwind
                customClass: {
                    icon: '!border-[10px] !text-red-600 text-xl !mt-5 !mb-0 !size-25 font-bold !border-red-600',
                    popup: '!bg-slate-300 dark:!bg-gray-800',
                    title: '!text-stone-900 !font-semibold dark:!text-white',
                    htmlContainer: '!text-stone-800 !font-medium dark:!text-gray-400',
                    confirmButton: '!bg-red-600 hover:!bg-red-400'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form jika dikonfirmasi
                }
            });
        }
    </script>
</body>

</html>
