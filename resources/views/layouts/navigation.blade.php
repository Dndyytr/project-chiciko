<nav x-data="{ open: false }"
    class="bg-white dark:bg-gray-800 sticky h-svh top-0 z-99 border-gray-100 w-full max-w-[17rem] dark:border-gray-700 overflow-y-auto">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-3 py-2">
        <div class="flex flex-col justify-between h-16">
            <div class="flex flex-col gap-2 pb-9">
                <!-- Logo -->
                <div class="w-max">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:flex flex-col gap-2">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <x-heroicon-s-chart-pie class="size-[1.3em]"></x-heroicon-s-chart-pie> {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        <x-heroicon-s-user class="size-[1.3em]"></x-heroicon-s-user> {{ __('User Management') }}
                    </x-nav-link>

                    {{-- Database Bahan --}}
                    <x-nav-link :href="route('database-materials.index')" :active="request()->routeIs('database-materials.index')">
                        <x-heroicon-s-inbox-stack class="size-[1.3em]"></x-heroicon-s-inbox-stack>
                        {{ __('Database Bahan') }}
                    </x-nav-link>

                    {{-- Pengaturan --}}
                    <div class="w-full" x-data="{ openPengaturan: false }">
                        {{-- Jika Daftar Perkiraan ada yang aktif --}}
                        @php
                            $isActivePengaturan =
                                request()->routeIs('list-accounting-estimates.index') ||
                                request()->routeIs('list-supplier-estimates.index') ||
                                request()->routeIs('list-color-estimates.index') ||
                                request()->routeIs('list-unit-measure-estimates.index') ||
                                request()->routeIs('lvl1-type-materials.index') ||
                                request()->routeIs('lvl2-type-materials.index') ||
                                request()->routeIs('lvl3-type-materials.index') ||
                                request()->routeIs('unit-internals.index') ||
                                request()->routeIs('unit-suppliers.index') ||
                                request()->routeIs('wages-estimates.index') ||
                                request()->routeIs('worksheet-abbreviations.index') ||
                                request()->routeIs('data-warehouses.index') ||
                                request()->routeIs('category-expenses.index') ||
                                request()->routeIs('coordinator-codes.index') ||
                                request()->routeIs('area-codes.index') ||
                                request()->routeIs('tailor-codes.index');
                        @endphp

                        {{-- Menu Pengaturan --}}
                        <div x-on:click="openPengaturan = !openPengaturan"
                            class="inline-flex cursor-pointer items-center justify-between w-full px-1 pt-1 gap-1 text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 transition duration-150 ease-in-out {{ $isActivePengaturan ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <p class="flex gap-1">
                                <x-heroicon-s-cog-6-tooth class="size-[1.3em]"></x-heroicon-s-cog-6-tooth>
                                <span>Pengaturan</span>
                            </p>
                            <span>
                                <svg :class="{ 'rotate-180': openPengaturan }"
                                    class="size-[1.5em] transition-transform duration-300 ease-in-out"
                                    xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </div>

                        {{-- Sub menu Pengaturan (Daftar Perkiraan) --}}
                        <div class="flex-col gap-2 flex" x-show="openPengaturan" style="display: none;"
                            x-transition:enter="transition duration-450 ease-out"
                            x-transition:enter-start="-translate-y-4 transform opacity-0"
                            x-transition:enter-end="translate-y-0 transform opacity-100"
                            x-transition:leave="transition duration-450 ease-in"
                            x-transition:leave-start="translate-y-0 transform opacity-100"
                            x-transition:leave-end="-translate-y-4 transform opacity-0">
                            <x-nav-link :href="route('list-accounting-estimates.index')" :active="request()->routeIs('list-accounting-estimates.index')">
                                — {{ __('Daftar Perkiraan Akuntansi') }}
                            </x-nav-link>
                            <x-nav-link :href="route('list-supplier-estimates.index')" :active="request()->routeIs('list-supplier-estimates.index')">
                                — {{ __('Daftar Perkiraan Supplier') }}
                            </x-nav-link>
                            <x-nav-link :href="route('list-color-estimates.index')" :active="request()->routeIs('list-color-estimates.index')">
                                — {{ __('Daftar Perkiraan Warna') }}
                            </x-nav-link>
                            <x-nav-link :href="route('list-unit-measure-estimates.index')" :active="request()->routeIs('list-unit-measure-estimates.index')">
                                — {{ __('Daftar Perkiraan Satuan Ukur') }}
                            </x-nav-link>
                            <x-nav-link :href="route('lvl1-type-materials.index')" :active="request()->routeIs('lvl1-type-materials.index')">
                                — {{ __('Jenis Bahan Level 1') }}
                            </x-nav-link>
                            <x-nav-link :href="route('lvl2-type-materials.index')" :active="request()->routeIs('lvl2-type-materials.index')">
                                — {{ __('Jenis Bahan Level 2') }}
                            </x-nav-link>
                            <x-nav-link :href="route('lvl3-type-materials.index')" :active="request()->routeIs('lvl3-type-materials.index')">
                                — {{ __('Jenis Bahan Level 3') }}
                            </x-nav-link>
                            <x-nav-link :href="route('unit-internals.index')" :active="request()->routeIs('unit-internals.index')">
                                — {{ __('Satuan Internal') }}
                            </x-nav-link>
                            <x-nav-link :href="route('unit-suppliers.index')" :active="request()->routeIs('unit-suppliers.index')">
                                — {{ __('Satuan Supplier') }}
                            </x-nav-link>
                            <x-nav-link :href="route('wages-estimates.index')" :active="request()->routeIs('wages-estimates.index')">
                                — {{ __('Perkiraan Upah') }}
                            </x-nav-link>
                            <x-nav-link :href="route('worksheet-abbreviations.index')" :active="request()->routeIs('worksheet-abbreviations.index')">
                                — {{ __('Singkatan Worksheet') }}
                            </x-nav-link>
                            <x-nav-link :href="route('data-warehouses.index')" :active="request()->routeIs('data-warehouses.index')">
                                — {{ __('Data Gudang') }}
                            </x-nav-link>
                            <x-nav-link :href="route('category-expenses.index')" :active="request()->routeIs('category-expenses.index')">
                                — {{ __('Kategori Pengeluaran') }}
                            </x-nav-link>
                            <x-nav-link :href="route('coordinator-codes.index')" :active="request()->routeIs('coordinator-codes.index')">
                                — {{ __('Kode Koordinator') }}
                            </x-nav-link>
                            <x-nav-link :href="route('area-codes.index')" :active="request()->routeIs('area-codes.index')">
                                — {{ __('Kode Daerah') }}
                            </x-nav-link>
                            <x-nav-link :href="route('tailor-codes.index')" :active="request()->routeIs('tailor-codes.index')">
                                — {{ __('Kode Penjahit') }}
                            </x-nav-link>

                        </div>
                    </div>

                    {{-- Pembelian Bahan Baku --}}
                    <div class="w-full" x-data="{ openPBB: false }">
                        {{-- Jika Pembelian Bahan Baku ada yang aktif --}}
                        @php
                            $isActivePBB =
                                request()->routeIs('incoming-raw-materials.index') ||
                                request()->routeIs('purchase-based-notes.index') ||
                                request()->routeIs('purchase-based-rolls.index') ||
                                request()->routeIs('purchase-based-yards.index');
                        @endphp

                        {{-- Menu Pembelian Bahan Baku --}}
                        <div x-on:click="openPBB = !openPBB"
                            class="inline-flex cursor-pointer items-center justify-between w-full px-1 pt-1 gap-1 text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 transition duration-150 ease-in-out {{ $isActivePBB ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <p class="flex gap-1">
                                <x-heroicon-s-inbox-arrow-down class="size-[1.3em]"></x-heroicon-s-inbox-arrow-down>
                                <span>Pembelian Bahan Baku</span>
                            </p>
                            <span>
                                <svg :class="{ 'rotate-180': openPBB }"
                                    class="size-[1.5em] transition-transform duration-300 ease-in-out"
                                    xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </div>

                        {{-- Sub Menu Pembelian Bahan Baku --}}
                        <div class="flex-col gap-2 flex" x-show="openPBB" style="display: none;"
                            x-transition:enter="transition duration-450 ease-out"
                            x-transition:enter-start="-translate-y-4 transform opacity-0"
                            x-transition:enter-end="translate-y-0 transform opacity-100"
                            x-transition:leave="transition duration-450 ease-in"
                            x-transition:leave-start="translate-y-0 transform opacity-100"
                            x-transition:leave-end="-translate-y-4 transform opacity-0">
                            <x-nav-link :href="route('incoming-raw-materials.index')" :active="request()->routeIs('incoming-raw-materials.index')">
                                — {{ __('Input Bahan Baku') }}
                            </x-nav-link>
                            <x-nav-link :href="route('purchase-based-notes.index')" :active="request()->routeIs('purchase-based-notes.index')">
                                — {{ __('Pembelian Berdasarkan Nota') }}
                            </x-nav-link>
                            <x-nav-link :href="route('purchase-based-rolls.index')" :active="request()->routeIs('purchase-based-rolls.index')">
                                — {{ __('Pembelian Berdasarkan Roll') }}
                            </x-nav-link>
                            <x-nav-link :href="route('purchase-based-yards.index')" :active="request()->routeIs('purchase-based-yards.index')">
                                — {{ __('Pembelian Berdasarkan Yard') }}
                            </x-nav-link>
                        </div>
                    </div>

                    {{-- BM Pelengkap  --}}
                    <div class="w-full" x-data="{ openBP: false }">
                        {{-- Jika Bahan Baku dan database bahan Aktif, caranya: tambahkan request()->routeIs('route halamannya') kedalam variabel php (bebas nama variabel mah) --}}
                        @php
                            $isActiveBP =
                                request()->routeIs('incoming-complement-materials.index') ||
                                request()->routeIs('complement-based-notes.index') ||
                                request()->routeIs('complement-based-materials.index');
                        @endphp

                        {{-- Menu Bahan Baku. panggil fungsi on click yaitu funsi ketika elemen diklik maka variabel akan bernilai true atau false, maksudnya openBB = !openBB adalah ketika elemen itu diklik makan openBB yang nilai false ketika diklik akan menjadi true, dan ketika openBB bernilai true lalu diklik maka akan menjadi false, begitu terus ketika terus terusan diklik --}}
                        <div x-on:click="openBP = !openBP"
                            class="inline-flex cursor-pointer items-center justify-between w-full px-1 pt-1 gap-1 text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 transition duration-150 ease-in-out {{ $isActiveBP ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            {{-- ketika isActiveBB bernilai true atau halaman yang anda buka ada di isi isActiveBB, maka isi class nya text-gray-700 dark:text-gray-300 tapi ketika tidak maka yg sebelah kanan itu ahh capek panjang --}}
                            <p class="flex gap-1">
                                <x-heroicon-s-puzzle-piece class="size-[1.3em]"></x-heroicon-s-puzzle-piece>
                                <span>Bahan Pelengkap</span>
                            </p>
                            {{-- icon panah kebawah akan berputar keatas 180 derajat ketika openBB bernilai true, tapi ketika bernilai false maka balik lagi ke semula --}}
                            <span>
                                <svg :class="{ 'rotate-180': openBP }"
                                    class="size-[1.5em] transition-transform duration-300 ease-in-out"
                                    xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </div>

                        {{-- Sub menu Bahan Baku, ini adalah elemen yang muncul dan bersembunyi sesuai nilai openBB, jadi ketika openBB tadi diklik menghasilkan nilai true maka sub menu ini akan muncul, dan ketika false maka akan bersembunyi  --}}
                        {{-- Penjelasan arti code
                        x-show : adalah menampilkan elemen/sub menu ini sesuai nilai false atau true nya openBB.
                        style="display: none;" : adalah ketika elemen/sub menu ini pertama kali direload atau website muncul kan dia bersembunyi dulu, nah ketika bersembunyi itu elemenya tidak ada bekas terlihat jadi benar benar sembunyi dengan style css hidden yg ada di app.css, jadi hanya kelihatan ketika openBB true, lebih jelasnya coba anda refresh website lihat perbedaan ketika direfresh ketika ada style="display: none;" dan ketika style="display: none;" tidak diterapkan pasti kelihatan bedanya.
                        x-transition : adalah ketika openBB bernilai true maka elemen/sub menu ini akan muncul dengan animasi sesuai nama seperti enter berati ketika dia masuk animasinya apa.      
                        --}}
                        <div class="flex-col gap-2 flex" x-show="openBP" style="display: none;"
                            x-transition:enter="transition duration-450 ease-out"
                            x-transition:enter-start="-translate-y-4 transform opacity-0"
                            x-transition:enter-end="translate-y-0 transform opacity-100"
                            x-transition:leave="transition duration-450 ease-in"
                            x-transition:leave-start="translate-y-0 transform opacity-100"
                            x-transition:leave-end="-translate-y-4 transform opacity-0">
                            <x-nav-link :href="route('incoming-complement-materials.index')" :active="request()->routeIs('incoming-complement-materials.index')">
                                — {{ __('Bahan Pelengkap') }}
                            </x-nav-link>
                            <x-nav-link :href="route('complement-based-notes.index')" :active="request()->routeIs('complement-based-notes.index')">
                                — {{ __('Bahan Pelengkap Nota') }}
                            </x-nav-link>
                            <x-nav-link :href="route('complement-based-materials.index')" :active="request()->routeIs('complement-based-materials.index')">
                                — {{ __('Bahan Pelengkap Nama Bahan') }}
                            </x-nav-link>
                        </div>
                    </div>

                    {{-- Stock Opname --}}
                    <x-nav-link :href="route('stock-opnames.index')" :active="request()->routeIs('stock-opnames.index')">
                        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                        <svg class="size-[1.3em]" viewBox="0 0 24 24" version="1.1" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <defs>
                                <path
                                    d="M19.6554561,20.9177505 L20.1324211,21.5082919 C19.7289347,21.8246698 19.2287757,22 18.7024516,22 C18.5957479,22 18.4898379,21.9928085 18.385261,21.9785596 L18.4901851,21.2309569 C18.5600191,21.240472 18.6308721,21.245283 18.7024516,21.245283 C19.0539479,21.245283 19.3863488,21.1287603 19.6554561,20.9177505 Z M13.2918622,17.3791385 C14.5579751,17.3791385 15.5843624,18.3904426 15.5843624,19.6379494 C15.5843624,20.8854563 14.5579751,21.8967604 13.2918622,21.8967604 C12.0257494,21.8967604 10.9993621,20.8854563 10.9993621,19.6379494 C10.9993621,18.3904426 12.0257494,17.3791385 13.2918622,17.3791385 Z M17.2015818,20.040198 C17.2869873,20.451794 17.5449795,20.8093683 17.9081786,21.0268796 L17.510337,21.6718118 C16.9661345,21.3459018 16.579504,20.8100362 16.4511322,20.1913721 L17.2015818,20.040198 Z M10.3971131,2 C11.5991439,2 12.616247,2.76528302 13.0045954,3.82210243 L13.0045954,3.82210243 L15.9449479,3.82210243 C16.9620509,3.82210243 17.7942261,4.64204852 17.7942261,5.64420485 L17.7942261,5.64420485 L17.7942261,11.1105121 L15.9449479,11.1105121 L15.9449479,5.64420485 L14.0956696,5.64420485 L14.0956696,8.37735849 L6.69855654,8.37735849 L6.69855654,5.64420485 L4.84927827,5.64420485 L4.84927827,19.309973 L9.47247394,19.309973 L9.47247394,21.1320755 L4.84927827,21.1320755 C3.83217522,21.1320755 3,20.3121294 3,19.309973 L3,19.309973 L3,5.64420485 C3,4.64204852 3.83217522,3.82210243 4.84927827,3.82210243 L4.84927827,3.82210243 L7.78963072,3.82210243 C8.17797915,2.76528302 9.1950822,2 10.3971131,2 Z M20.8598019,18.9546151 C20.9472125,19.1888929 20.9948768,19.436931 20.9999984,19.6977675 C21.0004225,20.0933426 20.9155279,20.4469131 20.7476245,20.7690374 L20.0662579,20.4242448 C20.1778966,20.2100648 20.2343216,19.9750652 20.2340973,19.7055583 C20.2307241,19.5358936 20.1990081,19.370848 20.1408704,19.2150275 L20.8598019,18.9546151 Z M17.5210599,17.793504 L17.9153266,18.440564 C17.550937,18.6561162 17.2909665,19.0123847 17.203282,19.4234195 L16.4536885,19.2681768 C16.5854849,18.6503604 16.9750506,18.1164922 17.5210599,17.793504 Z M18.7024516,17.4716981 C19.350097,17.4716981 19.9554053,17.7375693 20.3872523,18.1961485 L19.8257551,18.7094874 C19.5373774,18.4032585 19.1347584,18.2264151 18.7024516,18.2264151 C18.6390357,18.2269825 18.5619322,18.2313948 18.4998951,18.2394494 L18.3998271,17.4912006 C18.4927197,17.4791399 18.6078739,17.4725444 18.7024516,17.4716981 Z M18.5597349,12.1886792 C19.8258477,12.1886792 20.852235,13.1999834 20.852235,14.4474902 C20.852235,15.6949971 19.8258477,16.7063012 18.5597349,16.7063012 C17.293622,16.7063012 16.2672347,15.6949971 16.2672347,14.4474902 C16.2672347,13.1999834 17.293622,12.1886792 18.5597349,12.1886792 Z M13.2918622,12.1886792 C14.5579751,12.1886792 15.5843624,13.1999834 15.5843624,14.4474902 C15.5843624,15.6949971 14.5579751,16.7063012 13.2918622,16.7063012 C12.0257494,16.7063012 10.9993621,15.6949971 10.9993621,14.4474902 C10.9993621,13.1999834 12.0257494,12.1886792 13.2918622,12.1886792 Z M10.3971131,3.82210243 C9.88856155,3.82210243 9.47247394,4.23207547 9.47247394,4.73315364 C9.47247394,5.23423181 9.88856155,5.64420485 10.3971131,5.64420485 C10.9056646,5.64420485 11.3217522,5.23423181 11.3217522,4.73315364 C11.3217522,4.23207547 10.9056646,3.82210243 10.3971131,3.82210243 Z"
                                    id="path-1">

                                </path>
                            </defs>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <mask id="mask-2" fill="white">
                                    <use xlink:href="#path-1">
                                    </use>
                                </mask>
                                <use fill="currentColor" fill-rule="nonzero" xlink:href="#path-1">
                                </use>
                            </g>
                        </svg> {{ __('Stock Opname') }}
                    </x-nav-link>

                    {{-- Stock Persediaan --}}
                    <div class="w-full" x-data="{ openSP: false }">
                        {{-- Jika Bahan Baku dan database bahan Aktif, caranya: tambahkan request()->routeIs('route halamannya') kedalam variabel php (bebas nama variabel mah) --}}
                        @php
                            $isActiveSP =
                                request()->routeIs('stock-raw-materials.index') ||
                                request()->routeIs('stock-complement-materials.index');
                        @endphp

                        {{-- Menu Bahan Baku. panggil fungsi on click yaitu funsi ketika elemen diklik maka variabel akan bernilai true atau false, maksudnya openBB = !openBB adalah ketika elemen itu diklik makan openBB yang nilai false ketika diklik akan menjadi true, dan ketika openBB bernilai true lalu diklik maka akan menjadi false, begitu terus ketika terus terusan diklik --}}
                        <div x-on:click="openSP = !openSP"
                            class="inline-flex cursor-pointer items-center justify-between w-full px-1 pt-1 gap-1 text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 transition duration-150 ease-in-out {{ $isActiveSP ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            {{-- ketika isActiveBB bernilai true atau halaman yang anda buka ada di isi isActiveBB, maka isi class nya text-gray-700 dark:text-gray-300 tapi ketika tidak maka yg sebelah kanan itu ahh capek panjang --}}
                            <p class="flex gap-1">
                                <x-heroicon-s-rectangle-group class="size-[1.3em]"></x-heroicon-s-rectangle-group>
                                <span>Stock Persediaan</span>
                            </p>
                            {{-- icon panah kebawah akan berputar keatas 180 derajat ketika openBB bernilai true, tapi ketika bernilai false maka balik lagi ke semula --}}
                            <span>
                                <svg :class="{ 'rotate-180': openSP }"
                                    class="size-[1.5em] transition-transform duration-300 ease-in-out"
                                    xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </div>

                        {{-- Sub menu Bahan Baku, ini adalah elemen yang muncul dan bersembunyi sesuai nilai openBB, jadi ketika openBB tadi diklik menghasilkan nilai true maka sub menu ini akan muncul, dan ketika false maka akan bersembunyi  --}}
                        {{-- Penjelasan arti code
                        x-show : adalah menampilkan elemen/sub menu ini sesuai nilai false atau true nya openBB.
                        style="display: none;" : adalah ketika elemen/sub menu ini pertama kali direload atau website muncul kan dia bersembunyi dulu, nah ketika bersembunyi itu elemenya tidak ada bekas terlihat jadi benar benar sembunyi dengan style css hidden yg ada di app.css, jadi hanya kelihatan ketika openBB true, lebih jelasnya coba anda refresh website lihat perbedaan ketika direfresh ketika ada style="display: none;" dan ketika style="display: none;" tidak diterapkan pasti kelihatan bedanya.
                        x-transition : adalah ketika openBB bernilai true maka elemen/sub menu ini akan muncul dengan animasi sesuai nama seperti enter berati ketika dia masuk animasinya apa.      
                        --}}
                        <div class="flex-col gap-2 flex" x-show="openSP" style="display: none;"
                            x-transition:enter="transition duration-450 ease-out"
                            x-transition:enter-start="-translate-y-4 transform opacity-0"
                            x-transition:enter-end="translate-y-0 transform opacity-100"
                            x-transition:leave="transition duration-450 ease-in"
                            x-transition:leave-start="translate-y-0 transform opacity-100"
                            x-transition:leave-end="-translate-y-4 transform opacity-0">
                            <x-nav-link :href="route('stock-raw-materials.index')" :active="request()->routeIs('stock-raw-materials.index')">
                                — {{ __('SP Bahan Baku') }}
                            </x-nav-link>
                            <x-nav-link :href="route('stock-complement-materials.index')" :active="request()->routeIs('stock-complement-materials.index')">
                                — {{ __('SP Bahan Pelengkap') }}
                            </x-nav-link>

                        </div>
                    </div>

                    {{-- Pengeluaran --}}
                    <div class="w-full" x-data="{ openPL: false }">
                        {{-- Jika Bahan Baku dan database bahan Aktif, caranya: tambahkan request()->routeIs('route halamannya') kedalam variabel php (bebas nama variabel mah) --}}
                        @php
                            $isActivePL =
                                request()->routeIs('data-expenses.index') ||
                                request()->routeIs('summary-expenses.index');
                        @endphp

                        {{-- Menu Bahan Baku. panggil fungsi on click yaitu funsi ketika elemen diklik maka variabel akan bernilai true atau false, maksudnya openBB = !openBB adalah ketika elemen itu diklik makan openBB yang nilai false ketika diklik akan menjadi true, dan ketika openBB bernilai true lalu diklik maka akan menjadi false, begitu terus ketika terus terusan diklik --}}
                        <div x-on:click="openPL = !openPL"
                            class="inline-flex cursor-pointer items-center justify-between w-full px-1 pt-1 gap-1 text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 transition duration-150 ease-in-out {{ $isActivePL ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            {{-- ketika isActiveBB bernilai true atau halaman yang anda buka ada di isi isActiveBB, maka isi class nya text-gray-700 dark:text-gray-300 tapi ketika tidak maka yg sebelah kanan itu ahh capek panjang --}}
                            <p class="flex gap-1">
                                <svg class="size-[1.3em]" fill="currentColor" stroke="currentColor" width="100%"
                                    height="100%" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M985.6 247.467c-12.8-55.467-98.133-98.133-209.067-98.133-119.467 0-209.067 46.933-213.333 110.933V742.4c0 4.267 0 8.533 4.267 12.8 17.067 55.467 102.4 93.867 209.067 93.867 102.4 0 183.467-34.133 204.8-85.333 4.267-4.267 8.533-8.533 8.533-17.067v-486.4c4.267-4.267 0-8.533-4.267-12.8zM776.533 192c98.133 0 170.667 38.4 170.667 68.267 0 34.133-72.533 68.267-170.667 68.267s-170.667-38.4-170.667-68.267C610.133 230.4 682.666 192 776.533 192zm-166.4 136.533c38.4 25.6 98.133 42.667 170.667 42.667s132.267-17.067 170.667-46.933V384H947.2c0 34.133-72.533 68.267-170.667 68.267S605.866 413.867 605.866 384v-55.467zm0 123.734c38.4 25.6 98.133 42.667 170.667 42.667s132.267-17.067 170.667-46.933v42.667H947.2c0 34.133-72.533 68.267-170.667 68.267s-170.667-38.4-170.667-68.267v-38.4zm0 110.933c38.4 25.6 98.133 42.667 170.667 42.667s132.267-17.067 170.667-46.933v55.467H947.2c0 34.133-72.533 68.267-170.667 68.267s-170.667-38.4-170.667-68.267v-51.2zm166.4 243.2c-98.133 0-170.667-38.4-170.667-68.267v-55.467c38.4 25.6 98.133 42.667 170.667 42.667S908.8 708.266 947.2 678.4v55.467h-4.267C947.2 768 874.666 806.4 776.533 806.4zM119.467 298.667H64c-21.333 0-42.667 17.067-42.667 42.667v473.6c0 17.067 12.8 29.867 29.867 29.867H128c17.067 0 29.867-12.8 29.867-29.867v-473.6c4.267-21.333-17.067-42.667-38.4-42.667zm0 503.466H64v-460.8h55.467v460.8zm183.466-652.8H243.2c-21.333 0-42.667 17.067-42.667 42.667v627.2c0 17.067 12.8 29.867 29.867 29.867h76.8c17.067 0 29.867-12.8 29.867-29.867V192c4.267-25.6-12.8-42.667-34.133-42.667zM298.667 806.4h-51.2V192h55.467v614.4zm183.466-345.6h-55.467c-21.333 0-42.667 17.067-42.667 42.667v307.2c0 17.067 12.8 29.867 29.867 29.867h76.8c17.067 0 29.867-12.8 29.867-29.867V499.2c4.267-17.067-12.8-38.4-38.4-38.4zm0 341.333h-55.467v-294.4h55.467v294.4z" />
                                </svg>
                                <span>Pengeluaran</span>
                            </p>
                            {{-- icon panah kebawah akan berputar keatas 180 derajat ketika openBB bernilai true, tapi ketika bernilai false maka balik lagi ke semula --}}
                            <span>
                                <svg :class="{ 'rotate-180': openPL }"
                                    class="size-[1.5em] transition-transform duration-300 ease-in-out"
                                    xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </div>

                        {{-- Sub menu Bahan Baku, ini adalah elemen yang muncul dan bersembunyi sesuai nilai openBB, jadi ketika openBB tadi diklik menghasilkan nilai true maka sub menu ini akan muncul, dan ketika false maka akan bersembunyi  --}}
                        {{-- Penjelasan arti code
                        x-show : adalah menampilkan elemen/sub menu ini sesuai nilai false atau true nya openBB.
                        style="display: none;" : adalah ketika elemen/sub menu ini pertama kali direload atau website muncul kan dia bersembunyi dulu, nah ketika bersembunyi itu elemenya tidak ada bekas terlihat jadi benar benar sembunyi dengan style css hidden yg ada di app.css, jadi hanya kelihatan ketika openBB true, lebih jelasnya coba anda refresh website lihat perbedaan ketika direfresh ketika ada style="display: none;" dan ketika style="display: none;" tidak diterapkan pasti kelihatan bedanya.
                        x-transition : adalah ketika openBB bernilai true maka elemen/sub menu ini akan muncul dengan animasi sesuai nama seperti enter berati ketika dia masuk animasinya apa.      
                        --}}
                        <div class="flex-col gap-2 flex" x-show="openPL" style="display: none;"
                            x-transition:enter="transition duration-450 ease-out"
                            x-transition:enter-start="-translate-y-4 transform opacity-0"
                            x-transition:enter-end="translate-y-0 transform opacity-100"
                            x-transition:leave="transition duration-450 ease-in"
                            x-transition:leave-start="translate-y-0 transform opacity-100"
                            x-transition:leave-end="-translate-y-4 transform opacity-0">
                            <x-nav-link :href="route('data-expenses.index')" :active="request()->routeIs('data-expenses.index')">
                                — {{ __('Data Pengeluaran') }}
                            </x-nav-link>
                            <x-nav-link :href="route('summary-expenses.index')" :active="request()->routeIs('summary-expenses.index')">
                                — {{ __('Rekapan Pengeluaran') }}
                            </x-nav-link>
                        </div>
                    </div>

                    {{-- Data Penjahit --}}
                    <x-nav-link :href="route('data-tailors.index')" :active="request()->routeIs('data-tailors.index')">
                        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                        <svg class="size-[1.3em]" fill="currentColor" height="100%" width="100%" version="1.1"
                            id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001"
                            xml:space="preserve">
                            <g>
                                <g>
                                    <g>
                                        <polygon
                                            points="337.085,236.225 374.785,246.44 512.001,163.385 512.001,132.87 			" />
                                        <path
                                            d="M314.083,229.995l197.914-116.949V96.713c0-6.656-2.389-12.706-6.238-17.562l-235.29,139.034L314.083,229.995z" />
                                        <polygon
                                            points="479.297,292.43 238.93,227.329 238.93,262.179 436.067,316.605 			" />
                                        <path d="M489.523,68.924c-1.929-0.418-3.917-0.657-5.965-0.657h-39.825V28.442C443.733,12.766,430.968,0,415.292,0h-79.65
    C319.966,0,307.2,12.766,307.2,28.442v39.825h-39.825c-15.684,0-28.442,12.766-28.442,28.442v61.79l130.654-73.165h34.927
    l-165.581,92.723v31.59l8.533,2.313L489.523,68.924z M400.06,68.267h-75.793V28.442c0-6.272,5.103-11.375,11.375-11.375h79.65
    c6.272,0,11.375,5.103,11.375,11.375v39.825H400.06z" />
                                        <path d="M321.569,400.632l-0.478-0.162l-69.12,38.664c4.446,2.884,9.719,4.599,15.403,4.599h39.825v39.825
    c0,15.676,12.766,28.442,28.45,28.442h79.642c15.676,0,28.442-12.766,28.442-28.442v-39.825h39.825
    c15.684,0,28.442-12.766,28.442-28.442v-60.544l-122.266,71.919h-33.655l155.921-91.716v-41.259L321.74,400.111L321.569,400.632z
    M324.266,443.734h2.799h33.655h65.946v39.825c0,6.272-5.103,11.375-11.375,11.375h-79.642c-6.281,0-11.383-5.103-11.383-11.375
    V443.734z" />
                                        <polygon
                                            points="238.933,355.096 319.343,381.899 355.465,361.692 238.933,329.564 			" />
                                        <polygon
                                            points="414.874,328.463 238.934,279.883 238.934,311.866 376.662,349.839 			" />
                                        <polygon
                                            points="511.997,183.333 397.548,252.607 435.522,262.889 511.997,216.46 			" />
                                        <polygon
                                            points="500.599,280.515 512,274.141 512,236.423 458.257,269.046 			" />
                                        <path
                                            d="M238.933,373.085v42.206c0,3.686,0.759,7.194,2.048,10.436l58.206-32.555L238.933,373.085z" />
                                        <path d="M221.867,8.534c0-4.71-3.823-8.533-8.533-8.533c-4.71,0-8.533,3.823-8.533,8.533c0,25.574-18.867,46.771-43.409,50.543
    l0.742-7.876c0-28.237-22.963-51.2-51.2-51.2s-51.2,22.963-51.166,52.002l0.734,7.731H51.2c-28.237,0-51.2,22.963-51.2,51.2
    s22.963,51.2,51.2,51.2h18.961l14.49,153.6H51.2c-28.237,0-51.2,22.963-51.2,51.2c0,28.237,22.963,51.2,51.2,51.2h43.11
    l8.124,86.135c0.418,4.378,4.096,7.731,8.499,7.731s8.081-3.354,8.499-7.731l8.115-86.135h60.186c4.71,0,8.533-3.823,8.533-8.533
    s-3.823-8.533-8.533-8.533h-58.581l10.462-110.933h31.053c28.237,0,51.2-22.963,51.2-51.2s-22.963-51.2-51.2-51.2h-21.402
    l10.487-111.241C194.517,73.362,221.867,44.101,221.867,8.534z M51.2,145.067c-18.825,0-34.133-15.309-34.133-34.133
    S32.375,76.8,51.2,76.8h10.914l6.434,68.267H51.2z M51.2,401.067c-18.825,0-34.133-15.309-34.133-34.133S32.375,332.8,51.2,332.8
    h35.063l6.434,68.267H51.2z M119.467,85.334c0,4.71-3.823,8.533-8.533,8.533s-8.533-3.823-8.533-8.533V51.2
    c0-4.71,3.823-8.533,8.533-8.533s8.533,3.823,8.533,8.533V85.334z M170.667,204.8c18.825,0,34.133,15.309,34.133,34.133
    s-15.309,34.133-34.133,34.133h-29.449l6.434-68.267H170.667z" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                        {{ __('Data Penjahit') }}
                    </x-nav-link>

                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                {{ __('User Management') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('database-materials.index')" :active="request()->routeIs('database-materials.index')">
                {{ __('database bahan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('list-accounting-estimates.index')" :active="request()->routeIs('list-accounting-estimates.index')">
                {{ __('Daftar Perkiraan Akuntansi') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('list-supplier-estimates.index')" :active="request()->routeIs('list-supplier-estimates.index')">
                {{ __('Daftar Perkiraan Supplier') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('list-color-estimates.index')" :active="request()->routeIs('list-color-estimates.index')">
                {{ __('Daftar Perkiraan Warna') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('list-unit-measure-estimates.index')" :active="request()->routeIs('list-unit-measure-estimates.index')">
                {{ __('Daftar Perkiraan Satuan Ukur') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lvl1-type-materials.index')" :active="request()->routeIs('lvl1-type-materials.index')">
                {{ __('Jenis Bahan Level 1') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lvl2-type-materials.index')" :active="request()->routeIs('lvl2-type-materials.index')">
                {{ __('Jenis Bahan Level 2') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lvl3-type-materials.index')" :active="request()->routeIs('lvl3-type-materials.index')">
                {{ __('Jenis Bahan Level 3') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('unit-internals.index')" :active="request()->routeIs('unit-internals.index')">
                {{ __('Satuan Internal') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('unit-suppliers.index')" :active="request()->routeIs('unit-suppliers.index')">
                {{ __('Satuan Supplier') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('wages-estimates.index')" :active="request()->routeIs('wages-estimates.index')">
                {{ __('Perkiraan Upah') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('worksheet-abbreviations.index')" :active="request()->routeIs('worksheet-abbreviations.index')">
                {{ __('Singkatan Worksheet') }}
            </x-responsive-nav-link>

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
