<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Database Bahan') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('database-materials.store') }}" x-data="DatabaseMaterialForm()">
                        @csrf
                        <!-- name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>


                        {{-- Jenis Persediaan --}}
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Text JP -->
                            <div class="select-search">
                                <x-input-label for="id_text_jp" :value="__('Text JP')" />
                                <select name="id_text_jp" id="id_text_jp"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_text_jp')" class="mt-2" />
                                <input type="hidden" name="text_jp" id="text_jp"
                                    x-model="$store.databaseMaterialForm.textJP">
                            </div>
                            <!-- Kode JP -->
                            <div class="select-search">
                                <x-input-label for="kode_jp" :value="__('kode JP')" />
                                <x-text-input id="kode_jp" class="block mt-1 w-full" type="text" name="kode_jp"
                                    x-model="$store.databaseMaterialForm.kodeJP" placeholder="Terisi Otomatis" readonly
                                    required />
                                <x-input-error :messages="$errors->get('kode_jp')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Level 1 Jenis Bahan --}}
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Text Level 1 Jenis Bahan -->
                            <div class="select-search">
                                <x-input-label for="id_text_lvl1_jb" :value="__('Text Level 1 Jenis Bahan')" />
                                <select name="id_text_lvl1_jb" id="id_text_lvl1_jb"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_text_lvl1_jb')" class="mt-2" />
                                <input type="hidden" name="text_lvl1_jb" id="text_lvl1_jb"
                                    x-model="$store.databaseMaterialForm.textLvl1JB">
                            </div>
                            <!-- Kode Level 1 Jenis Bahan -->
                            <div class="select-search">
                                <x-input-label for="kode_lvl1_jb" :value="__('kode Level 1 Jenis Bahan')" />
                                <x-text-input id="kode_lvl1_jb" class="block mt-1 w-full" type="text"
                                    name="kode_lvl1_jb" placeholder="Terisi Otomatis" readonly required
                                    x-model="$store.databaseMaterialForm.kodeLvl1JB" />
                                <x-input-error :messages="$errors->get('kode_lvl1_jb')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Level 2 Jenis Bahan --}}
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Text Level 2 Jenis Bahan -->
                            <div class="select-search">
                                <x-input-label for="id_text_lvl2_jb" :value="__('Text Level 2 Jenis Bahan')" />
                                <select name="id_text_lvl2_jb" id="id_text_lvl2_jb"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_text_lvl2_jb')" class="mt-2" />
                                <input type="hidden" name="text_lvl2_jb" id="text_lvl2_jb"
                                    x-model="$store.databaseMaterialForm.textLvl2JB">
                            </div>
                            <!-- Kode Level 2 Jenis Bahan -->
                            <div class="select-search">
                                <x-input-label for="kode_lvl2_jb" :value="__('kode Level 2 Jenis Bahan')" />
                                <x-text-input id="kode_lvl2_jb" class="block mt-1 w-full" type="text"
                                    name="kode_lvl2_jb" placeholder="Terisi Otomatis" readonly required
                                    x-model="$store.databaseMaterialForm.kodeLvl2JB" />
                                <x-input-error :messages="$errors->get('kode_lvl2_jb')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Level 3 Jenis Bahan --}}
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Text Level 3 Jenis Bahan -->
                            <div class="select-search">
                                <x-input-label for="id_text_lvl3_jb" :value="__('Text Level 3 Jenis Bahan')" />
                                <select name="id_text_lvl3_jb" id="id_text_lvl3_jb"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_text_lvl3_jb')" class="mt-2" />
                                <input type="hidden" name="text_lvl3_jb" id="text_lvl3_jb"
                                    x-model="$store.databaseMaterialForm.textLvl3JB">
                            </div>
                            <!-- Kode Level 3 Jenis Bahan -->
                            <div class="select-search">
                                <x-input-label for="kode_lvl3_jb" :value="__('kode Level 3 Jenis Bahan')" />
                                <x-text-input id="kode_lvl3_jb" class="block mt-1 w-full" type="text"
                                    name="kode_lvl3_jb" placeholder="Terisi Otomatis" readonly required
                                    x-model="$store.databaseMaterialForm.kodeLvl3JB" />
                                <x-input-error :messages="$errors->get('kode_lvl3_jb')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Warna --}}
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Text Warna -->
                            <div class="select-search">
                                <x-input-label for="id_text_warna" :value="__('Text Warna')" />
                                <select name="id_text_warna" id="id_text_warna"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_text_warna')" class="mt-2" />
                                <input type="hidden" name="text_warna" id="text_warna"
                                    x-model="$store.databaseMaterialForm.textWarna">
                            </div>
                            <!-- Kode Warna -->
                            <div class="select-search">
                                <x-input-label for="kode_warna" :value="__('kode Warna')" />
                                <x-text-input id="kode_warna" class="block mt-1 w-full" type="text"
                                    name="kode_warna" placeholder="Terisi Otomatis" readonly required
                                    x-model="$store.databaseMaterialForm.kodeWarna" />
                                <x-input-error :messages="$errors->get('kode_warna')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <div>
                                <x-input-label for="kode_bahan" :value="__('Kode Bahan')" />
                                <x-text-input id="kode_bahan" class="block mt-1 w-full" type="text"
                                    name="kode_bahan" placeholder="Terisi Otomatis" readonly required
                                    x-model="$store.databaseMaterialForm.kodeBahan" />
                                <x-input-error :messages="$errors->get('kode_bahan')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label :value="__('Status')" />
                                <div class="flex items-center space-x-4 mt-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="status" value="Aktif"
                                            {{ old('status', 'Aktif') == 'Aktif' ? 'checked' : '' }}
                                            class="text-indigo-600 focus:ring-indigo-500
                            border-gray-300">
                                        <span class="ml-2">Aktif</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="status" value="Non Aktif"
                                            {{ old('status') == 'Non Aktif' ? 'checked' : '' }}
                                            class="text-indigo-600 focus:ring-indigo-500
                            border-gray-300">
                                        <span class="ml-2">Non Aktif</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('database-materials.index') }}"
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
        Alpine.store('databaseMaterialForm', {
            // Jenis Persediaan
            idTextJP: '',
            textJP: '',
            kodeJP: '',

            // Level 1 Jenis Bahan
            idTextLvl1JB: '',
            textLvl1JB: '',
            kodeLvl1JB: '',

            // Level 2 Jenis Bahan
            idTextLvl2JB: '',
            textLvl2JB: '',
            kodeLvl2JB: '',

            // Level 3 Jenis Bahan
            idTextLvl3JB: '',
            textLvl3JB: '',
            kodeLvl3JB: '',

            // Warna
            idTextWarna: '',
            textWarna: '',
            kodeWarna: '',


            // Update Jenis Persediaan
            updateKodeJP() {
                const listAccounting = window.dropdownData?.listAccountingEstimates.find(la => la
                    .value === this.idTextJP);
                this.textJP = listAccounting?.nama || '';
                this.kodeJP = listAccounting?.kode || '';
            },

            // Update Level 1 Jenis Bahan
            updateKodeLvl1JB() {
                const lvl1Type = window.dropdownData?.lvl1TypeMaterials.find(lvl1 => lvl1.value === this
                    .idTextLvl1JB);
                this.textLvl1JB = lvl1Type?.nama || '';
                this.kodeLvl1JB = lvl1Type?.kode || '';
            },

            // Update Level 2 Jenis Bahan
            updateKodeLvl2JB() {
                const lvl2Type = window.dropdownData?.lvl2TypeMaterials.find(lvl2 => lvl2.value === this
                    .idTextLvl2JB);
                this.textLvl2JB = lvl2Type?.nama || '';
                this.kodeLvl2JB = lvl2Type?.kode || '';
            },

            // Update Level 3 Jenis Bahan
            updateKodeLvl3JB() {
                const lvl3Type = window.dropdownData?.lvl3TypeMaterials.find(lvl3 => lvl3.value === this
                    .idTextLvl3JB);
                this.textLvl3JB = lvl3Type?.nama || '';
                this.kodeLvl3JB = lvl3Type?.kode || '';
            },

            // Update Warna
            updateKodeWarna() {
                const listColor = window.dropdownData?.listColorEstimates.find(lc => lc.value === this
                    .idTextWarna);
                this.textWarna = listColor?.warna || '';
                this.kodeWarna = listColor?.kode || '';
            },

            // Kode Bahan (Gabungan Semua Kode)
            get kodeBahan() {
                const parts = [
                    this.kodeJP,
                    this.kodeLvl1JB,
                    this.kodeLvl2JB,
                    this.kodeLvl3JB,
                    this.kodeWarna
                ].filter(part => part !== '');

                return parts.join('.');
            },
        });
    })


    function DatabaseMaterialForm() {
        return {
            get textJP() {
                return Alpine.store('databaseMaterialForm').textJP
            },
            set textJP(value) {
                Alpine.store('databaseMaterialForm').textJP = value
            },

            get kodeJP() {
                return Alpine.store('databaseMaterialForm').kodeJP
            },
            set kodeJP(value) {
                Alpine.store('databaseMaterialForm').kodeJP = value
            },

            get textLvl1JB() {
                return Alpine.store('databaseMaterialForm').textLvl1JB
            },
            set textLvl1JB(value) {
                Alpine.store('databaseMaterialForm').textLvl1JB = value
            },

            get kodeLvl1JB() {
                return Alpine.store('databaseMaterialForm').kodeLvl1JB
            },
            set kodeLvl1JB(value) {
                Alpine.store('databaseMaterialForm').kodeLvl1JB = value
            },
            get textLvl2JB() {
                return Alpine.store('databaseMaterialForm').textLvl2JB
            },
            set textLvl2JB(value) {
                Alpine.store('databaseMaterialForm').textLvl2JB = value
            },
            get kodeLvl2JB() {
                return Alpine.store('databaseMaterialForm').kodeLvl2JB
            },
            set kodeLvl2JB(value) {
                Alpine.store('databaseMaterialForm').kodeLvl2JB = value
            },

            get textLvl3JB() {
                return Alpine.store('databaseMaterialForm').textLvl3JB
            },
            set textLvl3JB(value) {
                Alpine.store('databaseMaterialForm').textLvl3JB = value
            },

            get kodeLvl3JB() {
                return Alpine.store('databaseMaterialForm').kodeLvl3JB
            },
            set kodeLvl3JB(value) {
                Alpine.store('databaseMaterialForm').kodeLvl3JB = value
            },

            get textWarna() {
                return Alpine.store('databaseMaterialForm').textWarna
            },
            set textWarna(value) {
                Alpine.store('databaseMaterialForm').textWarna = value
            },

            get kodeWarna() {
                return Alpine.store('databaseMaterialForm').kodeWarna
            },
            set kodeWarna(value) {
                Alpine.store('databaseMaterialForm').kodeWarna = value
            },

            get kodeBahan() {
                return Alpine.store('databaseMaterialForm').kodeBahan
            },
            set kodeBahan(value) {
                Alpine.store('databaseMaterialForm').kodeBahan = value
            }

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
        const dropdowns = ['id_text_jp', 'id_text_lvl1_jb', 'id_text_lvl2_jb', 'id_text_lvl3_jb', 'id_text_warna'];
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
            const response = await fetch('/admin/api/database-materials/data');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            window.dropdownData = data; // Simpan ke global

            return data; // Kembalikan data
        } catch (error) {
            console.error('Error preloading initial ', error);
            const emptyData = {
                listAccountingEstimates: [],
                lvl1TypeMaterials: [],
                lvl2TypeMaterials: [],
                lvl3TypeMaterials: [],
                listColorEstimates: [],
            };
            window.dropdownData = emptyData;
            return emptyData;
        }
    }

    let lastRequestTime = 0;
    let searchTimeout;

    function initializeTomSelectWithSearch(initialData) {
        const configs = [{
                id: 'id_text_jp',
                type: 'listAccountingEstimates',
                placeholder: '-- Pilih Text JP --'
            },
            {
                id: 'id_text_lvl1_jb',
                type: 'lvl1TypeMaterials',
                placeholder: '-- Pilih Text Level 1 Jenis Bahan --'
            },
            {
                id: 'id_text_lvl2_jb',
                type: 'lvl2TypeMaterials',
                placeholder: '-- Pilih Text Level 2 Jenis Bahan --'
            },
            {
                id: 'id_text_lvl3_jb',
                type: 'lvl3TypeMaterials',
                placeholder: '-- Pilih Text Level 3 Jenis Bahan --'
            },
            {
                id: 'id_text_warna',
                type: 'listColorEstimates',
                placeholder: '-- Pilih Text Warna --'
            }
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
                                `/admin/api/database-materials/data?q=${encodeURIComponent(query)}`
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
                    const store = Alpine.store('databaseMaterialForm');

                    switch (config.id) {
                        case 'id_text_jp':
                            store.idTextJP = value;
                            store.updateKodeJP();
                            break;
                        case 'id_text_lvl1_jb':
                            store.idTextLvl1JB = value;
                            store.updateKodeLvl1JB();
                            break;
                        case 'id_text_lvl2_jb':
                            store.idTextLvl2JB = value;
                            store.updateKodeLvl2JB();
                            break;
                        case 'id_text_lvl3_jb':
                            store.idTextLvl3JB = value;
                            store.updateKodeLvl3JB();
                            break;
                        case 'id_text_warna':
                            store.idTextWarna = value;
                            store.updateKodeWarna();
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
