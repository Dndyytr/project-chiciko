<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Kode Penjahit') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('tailor-codes.update', $tailorCode->id) }}"
                        x-data="tailorCodeForm()" class="w-full">
                        @csrf
                        @method('PUT')

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Nama Koordinator --}}
                            <div class="select-search">
                                <x-input-label for="id_nama_koordinator" :value="__('Nama Koordinator')" />
                                <select name="id_nama_koordinator" id="id_nama_koordinator"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_nama_koordinator')" class="mt-2" />
                                <input type="hidden" name="nama_koordinator" id="nama_koordinator"
                                    x-model="$store.tailorCodeForm.namaKoordinator"
                                    value="{{ old('nama_koordinator', $tailorCode->nama_koordinator) }}">
                            </div>

                            {{-- Kode Koordinator --}}
                            <div>
                                <x-input-label for="kode_koordinator" :value="__('Kode Koordinator')" />
                                <x-text-input id="kode_koordinator" name="kode_koordinator" type="text"
                                    class="mt-1 block w-full" placeholder="Terisi Otomatis" required readonly
                                    x-model="$store.tailorCodeForm.kodeKoordinator" :value="old('kode_koordinator', $tailorCode->kode_koordinator)" />
                                <x-input-error :messages="$errors->get('kode_koordinator')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4 mt-2">
                            {{-- Nama Daerah --}}
                            <div class="select-search">
                                <x-input-label for="id_nama_daerah" :value="__('Nama Daerah')" />
                                <select name="id_nama_daerah" id="id_nama_daerah"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_nama_daerah')" class="mt-2" />
                                <input type="hidden" name="nama_daerah" id="nama_daerah"
                                    x-model="$store.tailorCodeForm.namaDaerah"
                                    value="{{ old('nama_daerah', $tailorCode->nama_daerah) }}">
                            </div>
                            {{-- Kode Daerah --}}
                            <div>
                                <x-input-label for="kode_daerah" :value="__('Kode Daerah')" />
                                <x-text-input name="kode_daerah" id="kode_daerah" type="text"
                                    class="mt-1 block w-full" placeholder="Terisi Otomatis" required readonly
                                    x-model="$store.tailorCodeForm.kodeDaerah" :value="old('kode_daerah', $tailorCode->kode_daerah)" />
                                <x-input-error :messages="$errors->get('kode_daerah')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Nama Penjahit --}}
                            <div>
                                <x-input-label for="nama_penjahit" :value="__('Nama Penjahit')" />
                                <x-text-input name="nama_penjahit" id="nama_penjahit" type="text"
                                    class="mt-1 block w-full" :value="old('nama_penjahit', $tailorCode->nama_penjahit)" required />
                                <x-input-error :messages="$errors->get('nama_penjahit')" class="mt-2" />
                            </div>

                            {{-- No Urut --}}
                            <div>
                                <x-input-label for="no_urut" :value="__('No Urut')" />
                                <x-text-input name="no_urut" id="no_urut" type="number" class="mt-1 block w-full"
                                    required x-model="$store.tailorCodeForm.noUrut" :value="old('no_urut', $tailorCode->no_urut)" />
                                <x-input-error :messages="$errors->get('no_urut')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Kode Penjahit --}}
                        <div>
                            <x-input-label for="kode_penjahit" :value="__('Kode Penjahit')" />
                            <x-text-input name="kode_penjahit" id="kode_penjahit" type="text"
                                class="mt-1 block w-full" placeholder="Terisi Otomatis" required readonly
                                x-model="$store.tailorCodeForm.kodePenjahit" :value="old('kode_penjahit', $tailorCode->kode_penjahit)" />
                            <x-input-error :messages="$errors->get('kode_penjahit')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tailor-codes.index') }}"
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
    const editData = {
        nama_koordinator: "{{ $tailorCode->nama_koordinator }}",
        kode_koordinator: "{{ $tailorCode->kode_koordinator }}",
        nama_daerah: "{{ $tailorCode->nama_daerah }}",
        kode_daerah: "{{ $tailorCode->kode_daerah }}",
        no_urut: "{{ $tailorCode->no_urut }}",
    };

    document.addEventListener('alpine:init', () => {
        Alpine.store('tailorCodeForm', {
            // inisial varaibel state
            idNamaKoordinator: '',
            idNamaDaerah: '',
            namaKoordinator: editData.nama_koordinator,
            namaDaerah: editData.nama_daerah,
            kodeKoordinator: editData.kode_koordinator,
            kodeDaerah: editData.kode_daerah,
            noUrut: editData.no_urut,

            // update kode koordinator
            updateKodeKoordinator() {
                const coordinatorCode = window.dropdownData?.coordinatorCodes.find(cc => cc.value ===
                    this.idNamaKoordinator);
                this.namaKoordinator = coordinatorCode?.nama_koordinator || '';
                this.kodeKoordinator = coordinatorCode?.kode || '';
            },

            // update kode daerah
            updateKodeDaerah() {
                const areaCode = window.dropdownData?.areaCodes.find(ac => ac.value === this
                    .idNamaDaerah);
                this.namaDaerah = areaCode?.nama_daerah || '';
                this.kodeDaerah = areaCode?.kode || '';
            },

            // update kode penjahit
            get kodePenjahit() {
                const parts = [
                    this.kodeKoordinator,
                    this.kodeDaerah,
                    this.noUrut
                ].filter(part => part !== '');

                return parts.join('');
            },
        });
    })

    function tailorCodeForm() {
        return {
            get namaKoordinator() {
                return Alpine.store('tailorCodeForm').namaKoordinator;
            },
            set namaKoordinator(value) {
                Alpine.store('tailorCodeForm').namaKoordinator = value;
            },

            get kodeKoordinator() {
                return Alpine.store('tailorCodeForm').kodeKoordinator;
            },
            set kodeKoordinator(value) {
                Alpine.store('tailorCodeForm').kodeKoordinator = value
            },

            get namaDaerah() {
                return Alpine.store('tailorCodeForm').namaDaerah;
            },
            set namaDaerah(value) {
                Alpine.store('tailorCodeForm').namaDaerah = value;
            },
            get kodeDaerah() {
                return Alpine.store('tailorCodeForm').kodeDaerah;
            },
            set kodeDaerah(value) {
                Alpine.store('tailorCodeForm').kodeDaerah = value;
            },

            get noUrut() {
                return Alpine.store('tailorCodeForm').noUrut;
            },
            set noUrut(value) {
                Alpine.store('tailorCodeForm').noUrut = value;
            },

            get kodePenjahit() {
                return Alpine.store('tailorCodeForm').kodePenjahit;
            },
            set kodePenjahit(value) {
                Alpine.store('tailorCodeForm').kodePenjahit = value;
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

        findIDsFromTexts();
        setInitialValuesFromInline();
    });

    function renderSkeletonDropdowns() {
        const dropdowns = ['id_nama_koordinator', 'id_nama_daerah'];
        dropdowns.forEach(id => {
            const el = document.getElementById(id);
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
        });
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
            const response = await fetch('/settings/api/tailor-codes/data');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            window.dropdownData = data; // Simpan ke global

            return data; // Kembalikan data
        } catch (error) {
            console.error('Error preloading initial ', error);
            const emptyData = {
                coordinatorCodess: [],
                areaCodes: [],
            };
            window.dropdownData = emptyData;
            return emptyData;
        }
    }

    // ✅ FUNGSI BARU: Cari ID dari text yang tersimpan
    function findIDsFromTexts() {
        const store = Alpine.store('tailorCodeForm');

        if (!window.dropdownData) return;

        // Find ID untuk Nama Koordinator
        const namaKoordinatorItem = window.dropdownData.coordinatorCodes.find(item =>
            item.nama_koordinator === editData.nama_koordinator && item.kode === editData.kode_koordinator
        );
        if (namaKoordinatorItem) {
            store.idNamaKoordinator = namaKoordinatorItem.value;
        }

        // Find ID untuk Nama Daerah
        const namaDaerahItem = window.dropdownData.areaCodes.find(item =>
            item.nama_daerah === editData.nama_daerah && item.kode === editData.kode_daerah
        );
        if (namaDaerahItem) {
            store.idNamaDaerah = namaDaerahItem.value;
        }

    }

    // ⚠️ SETEL NILAI DARI JSON INLINE
    function setInitialValuesFromInline() {
        const store = Alpine.store('tailorCodeForm');

        const configs = [{
                id: 'id_nama_koordinator',
                value: store.idNamaKoordinator
            },
            {
                id: 'id_nama_daerah',
                value: store.idNamaDaerah
            },
        ];

        configs.forEach(config => {
            const tomSelect = document.getElementById(config.id)?.tomselect;
            if (tomSelect && config.value) {
                // Coba set nilai
                setTimeout(() => {
                    if (tomSelect.options[config.value]) {
                        tomSelect.setValue(config.value, true);
                    }
                }, 100);
            }
        });
    }

    let lastRequestTime = 0;
    let searchTimeout;

    function initializeTomSelectWithSearch(initialData) {
        const configs = [{
                id: 'id_nama_koordinator',
                type: 'coordinatorCodes',
                placeholder: '-- Pilih Nama Koordinator --'
            },
            {
                id: 'id_nama_daerah',
                type: 'areaCodes',
                placeholder: '-- Pilih Nama Daerah --'
            },
        ];

        configs.forEach(config => {
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
                onInitialize: function() {
                    if (initialData && initialData[config.type]) {
                        initialData[config.type].forEach(item => this.addOption(item));
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
                                `/settings/api/tailor-codes/data?q=${encodeURIComponent(query)}`
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
                            .then(data => callback(data[config.type]))
                            .catch(() => callback());
                    }, 300);

                },

                onChange: function(value) {
                    // ⚠️ GUNAKAN STORE LANGSUNG, BUKAN COMPONENT
                    const store = Alpine.store('tailorCodeForm');

                    switch (config.id) {
                        case 'id_nama_koordinator':
                            store.idNamaKoordinator = value;
                            store.updateKodeKoordinator();
                            break;
                        case 'id_nama_daerah':
                            store.idNamaDaerah = value;
                            store.updateKodeDaerah();
                            break;
                    }
                }

            });

            addCloseAnimation(tomSelect);
        });
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
