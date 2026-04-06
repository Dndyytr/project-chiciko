<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Data Penjahit') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('data-tailors.store') }}" x-data="dataTailorForm()" class="w-full">
                        @csrf

                        {{-- Kode Penjahit --}}
                        <div class="select-search">
                            <x-input-label for="kode_penjahit" :value="__('Kode Penjahit')" />
                            <select name="kode_penjahit" id="kode_penjahit"
                                class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                            </select>
                            <x-input-error :messages="$errors->get('kode_penjahit')" class="mt-2" />
                        </div>
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Nama Koordinator --}}
                            <div>
                                <x-input-label for="nama_koordinator" :value="__('Nama Koordinator')" />
                                <x-text-input name="nama_koordinator" id="nama_koordinator" type="text"
                                    class="mt-1 block w-full" placeholder="Terisi Otomatis" required readonly
                                    x-model="$store.dataTailorForm.namaKoordinator" :value="old('nama_koordinator')" />
                                <x-input-error :messages="$errors->get('nama_koordinator')" class="mt-2" />
                            </div>
                            {{-- Kode Koordinator --}}
                            <div>
                                <x-input-label for="kode_koordinator" :value="__('Kode Koordinator')" />
                                <x-text-input id="kode_koordinator" name="kode_koordinator" type="text"
                                    class="mt-1 block w-full" placeholder="Terisi Otomatis" required readonly
                                    x-model="$store.dataTailorForm.kodeKoordinator" :value="old('kode_koordinator')" />
                                <x-input-error :messages="$errors->get('kode_koordinator')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4 mt-2">
                            {{-- Nama Daerah --}}
                            <div class="select-search">
                                <x-input-label for="nama_daerah" :value="__('Nama Daerah')" />
                                <x-text-input id="nama_daerah" name="nama_daerah" type="text"
                                    class="mt-1 block w-full" placeholder="Terisi Otomatis" required readonly
                                    x-model="$store.dataTailorForm.namaDaerah" :value="old('nama_daerah')" />
                                <x-input-error :messages="$errors->get('nama_daerah')" class="mt-2" />
                            </div>
                            {{-- Nama Penjahit --}}
                            <div class="select-search">
                                <x-input-label for="nama_penjahit" :value="__('Nama Penjahit')" />
                                <x-text-input id="nama_penjahit" name="nama_penjahit" type="text"
                                    class="mt-1 block w-full" placeholder="Terisi Otomatis" required readonly
                                    x-model="$store.dataTailorForm.namaPenjahit" :value="old('nama_penjahit')" />
                                <x-input-error :messages="$errors->get('nama_penjahit')" class="mt-2" />
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('data-tailors.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md
font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2
focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Back') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script defer>
    document.addEventListener('alpine:init', () => {
        Alpine.store('dataTailorForm', {
            // inisial varaibel state
            namaKoordinator: '',
            kodeKoordinator: '',
            namaPenjahit: '',
            kodePenjahit: '',
            namaDaerah: '',

            // update all
            updateAllField() {
                const selected = window.dropdownData?.find(n => n.value === this.kodePenjahit);
                if (selected) {
                    this.kodeKoordinator = selected.kode_koordinator
                    this.namaDaerah = selected.nama_daerah;
                    this.namaPenjahit = selected.nama_penjahit;
                    this.namaKoordinator = selected.nama_koordinator;
                }
            }

        });
    })

    function dataTailorForm() {
        return {
            get namaKoordinator() {
                return Alpine.store('dataTailorForm').namaKoordinator;
            },
            set namaKoordinator(value) {
                Alpine.store('dataTailorForm').namaKoordinator = value;
            },

            get kodeKoordinator() {
                return Alpine.store('dataTailorForm').kodeKoordinator;
            },
            set kodeKoordinator(value) {
                Alpine.store('dataTailorForm').kodeKoordinator = value
            },

            get namaDaerah() {
                return Alpine.store('dataTailorForm').namaDaerah;
            },
            set namaDaerah(value) {
                Alpine.store('dataTailorForm').namaDaerah = value;
            },

            get namaPenjahit() {
                return Alpine.store('dataTailorForm').namaPenjahit;
            },
            set namaPenjahit(value) {
                Alpine.store('dataTailorForm').namaPenjahit = value;
            },

            get kodePenjahit() {
                return Alpine.store('dataTailorForm').kodePenjahit;
            },
            set kodePenjahit(value) {
                Alpine.store('dataTailorForm').kodePenjahit = value;
            },
        }
    }

    document.addEventListener("DOMContentLoaded", async () => {
        if (typeof TomSelect === 'undefined') {
            await waitForTomSelect();
        }

        // RENDER SKELETON DULU
        renderSkeletonDropdowns();

        // TUNGGU DATA AWAL SELESAI DIMUAT
        const initialData = await preloadInitialData();

        // BARU INISIALISASI TOMSELECT
        initializeTomSelectWithSearch(initialData);
    });

    function renderSkeletonDropdowns() {
        const el = document.getElementById('kode_penjahit');
        if (!el) return;

        try {
            new TomSelect(el, {
                options: [],
                searchField: ['text'],
                placeholder: 'Memuat data...',
                loading: true,
            });
        } catch (error) {
            console.error(`Failed to create skeleton for ${id}:`, error);
        }
    }

    async function waitForTomSelect(maxWait = 5000) {
        const startTime = Date.now();
        while (Date.now() - startTime < maxWait) {
            if (typeof TomSelect !== 'undefined') return;
            await new Promise(resolve => setTimeout(resolve, 50));
        }
    }

    async function preloadInitialData() {
        try {
            const response = await fetch('/admin/api/data-tailors/data');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            window.dropdownData = data; // Simpan ke global

            return data; // Kembalikan data
        } catch (error) {
            console.error('Error preloading initial ', error);
            const emptyData = {
                coordinatorCodess: [],
                areaCodes: [],
                tailorCodes: [],
            };
            window.dropdownData = emptyData;
            return emptyData;
        }
    }

    let lastRequestTime = 0;
    let searchTimeout;

    function initializeTomSelectWithSearch(initialData) {
        const config = {
            id: 'kode_penjahit',
            placeholder: '-- Pilih Kode Penjahit --'
        };

        const el = document.getElementById(config.id);
        if (el.tomselect) {
            el.tomselect.destroy();
        }

        const tomSelect = new TomSelect(el, {
            valueField: 'value',
            labelField: 'text',
            searchField: 'text',
            placeholder: config.placeholder,

            // ⚠️ ISI DENGAN DATA YANG SUDAH SIAP
            onInitialize() {
                // Gunakan data yang sudah dipreload
                if (Array.isArray(initialData)) {
                    initialData.forEach(item => this.addOption(item));
                    this.refreshOptions(false);
                }
            },

            load: function(query, callback) {
                if (query.length < 2) return callback();

                // Debounce: tunggu 300ms setelah user berhenti mengetik
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Cek apakah sudah request dalam 1 detik terakhir
                    const now = Date.now();
                    if (now - lastRequestTime < 1000) {
                        console.log('Too frequent, using cached data');
                        return callback([]);
                    }

                    fetch(
                            `/admin/api/data-tailors/data?q=${encodeURIComponent(query)}`
                        )
                        .then(response => {
                            lastRequestTime = Date.now();
                            // Cek status cache
                            if (response.status === 304) {
                                console.log('Using cached response from browser');
                                return Promise.resolve(response);
                            }
                            return response.json();
                        })
                        .then(data => callback(data))
                        .catch(() => callback());
                }, 300);

            },

            onChange(value) {
                const store = Alpine.store('dataTailorForm');
                store.kodePenjahit = value;
                store.updateAllField();
            }

        });

        addCloseAnimation(tomSelect);
    }

    // Fungsi untuk menambahkan animasi close
    function addCloseAnimation(tomSelect) {
        let closeTimeout;
        const originalClose = tomSelect.close;

        tomSelect.close = function(setTextboxValue) {
            if (closeTimeout) {
                clearTimeout(closeTimeout);
            }

            if (this.dropdown) {
                this.dropdown.classList.add("slideup");
            }

            closeTimeout = setTimeout(() => {
                if (this.dropdown) {
                    this.dropdown.classList.remove("slideup");
                }
                originalClose.call(this, setTextboxValue);
                closeTimeout = null;
            }, 400);
        };
    }
</script>
