<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Stock Opname') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('stock-opnames.update', $stockOpname->id) }}"
                        x-data="stockOpnamesForm()">
                        @csrf
                        @method('PUT')
                        <!-- Nama Gudang -->
                        <div class="select-search mb-2">
                            <x-input-label for="nama_gudang" :value="__('Nama Gudang')" />
                            <select name="nama_gudang" id="nama_gudang"></select>
                            <x-input-error :messages="$errors->get('nama_gudang')" class="mt-2" />

                            {{-- hidden data warehouses id --}}
                            <input name="data_warehouses_id" id="data_warehouses_id" type="hidden"
                                x-model="$store.opnamesForm.dataWarehousesId" />
                        </div>

                        {{-- Search Material --}}
                        <x-table-search>
                            <x-slot name="trigger">
                                <button type="button"
                                    class="flex w-full items-center justify-center bg-indigo-600 hover:bg-indigo-900 transition-all duration-300 ease-in-out px-3 py-[6px] cursor-pointer text-white rounded-md shadow-sm">Klik
                                    Untuk Cari dan Pilih Barang Masuk <x-heroicon-s-magnifying-glass
                                        class="size-[1.3em]"></x-heroicon-s-magnifying-glass></button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="flex gap-2">
                                    <div class="w-full">
                                        <x-input-label for="search" :value="__('Cari')" />
                                        <input type="search" id="search" name="search" x-model="searchQuery"
                                            x-on:input.debounce.500ms="fetchMaterials()" placeholder="Cari..."
                                            class="w-full block border-gray-300 mt-1 dark:border-gray-700 ring-transparent px-3 py-[6px] dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 outline-none border-[1px] ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    </div>

                                    <div class="select-search w-full">
                                        <x-input-label for="jenis_bm" :value="__('Jenis BM')" />
                                        <select id="jenis_bm" name="jenis_bm" x-model="jenisBm"
                                            class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                            <option value="">-- Pilih Jenis BM --</option>
                                            <option value="App\Models\IncomingRawMaterial">BM Baku</option>
                                            <option value="App\Models\IncomingComplementMaterial">BM Pelengkap</option>
                                        </select>
                                    </div>
                                </div>
                            </x-slot>

                        </x-table-search>

                        {{-- Material morphs --}}
                        <input type="hidden" id="material_type" name="material_type"
                            value="{{ old('material_type', $stockOpname->material_type) }}">
                        <input type="hidden" id="material_id" name="material_id"
                            value="{{ old('material_id', $stockOpname->material_id) }}">

                        {{-- kode --}}
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4 mt-2">
                            {{-- kode item --}}
                            <div>
                                <x-input-label for="kode_item" :value="__('Kode Item')" />
                                <x-text-input id="kode_item" class="block mt-1 w-full" type="text" name="kode_item"
                                    placeholder="Terisi Otomatis" readonly required
                                    value="{{ old('kode_item', $stockOpname->kode_item) }}" />
                                <x-input-error :messages="$errors->get('kode_item')" class="mt-2" />
                            </div>

                            {{-- kode barcode --}}
                            <div>
                                <x-input-label for="kode_barcode" :value="__('Kode Barcode')" />
                                <x-text-input id="kode_barcode" class="block mt-1 w-full" type="text"
                                    name="kode_barcode" placeholder="Terisi Otomatis" readonly required
                                    value="{{ old('kode_barcode', $stockOpname->kode_barcode) }}" />
                                <x-input-error :messages="$errors->get('kode_barcode')" class="mt-2" />
                            </div>
                        </div>

                        {{-- nama item dan satuan --}}
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- nama item --}}
                            <div>
                                <x-input-label for="nama_item" :value="__('Nama Item')" />
                                <x-text-input id="nama_item" class="block mt-1 w-full" type="text" name="nama_item"
                                    placeholder="Terisi Otomatis" readonly required
                                    value="{{ old('nama_item', $stockOpname->nama_item) }}" />
                                <x-input-error :messages="$errors->get('nama_item')" class="mt-2" />
                            </div>

                            {{-- satuan --}}
                            <div>
                                <x-input-label for="satuan" :value="__('Satuan')" />
                                <x-text-input id="satuan" class="block mt-1 w-full" type="text" name="satuan"
                                    placeholder="Terisi Otomatis" readonly required
                                    value="{{ old('satuan', $stockOpname->satuan) }}" />
                                <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
                            </div>
                        </div>

                        {{-- buku dan fisik --}}
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- buku --}}
                            <div>
                                <x-input-label for="buku" :value="__('Buku')" />
                                <x-text-input id="buku" class="block mt-1 w-full" type="number" name="buku"
                                    min="0" placeholder="Terisi Otomatis" readonly required
                                    value="{{ old('buku', $stockOpname->buku) }}" />
                                <x-input-error :messages="$errors->get('buku')" class="mt-2" />
                            </div>

                            {{-- fisik --}}
                            <div>
                                <x-input-label for="fisik" :value="__('Fisik')" />
                                <x-text-input id="fisik" class="block mt-1 w-full" type="number" name="fisik"
                                    min="0" x-on:input="calculateSelisih()" required
                                    value="{{ old('fisik', $stockOpname->fisik) }}" />
                                <x-input-error :messages="$errors->get('fisik')" class="mt-2" />
                            </div>
                        </div>

                        <x-input-label for="selisih" :value="__('Selisih')" />
                        <x-text-input id="selisih" class="block mt-1 w-full" type="number" name="selisih"
                            min="0" placeholder="Terisi Otomatis" readonly required
                            value="{{ old('selisih', $stockOpname->selisih) }}" />
                        <x-input-error :messages="$errors->get('selisih')" class="mt-2" />


                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('stock-opnames.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border
border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest
hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500
focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
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
    const editData = @json($stockOpname);

    document.addEventListener('alpine:init', () => {
        Alpine.store('opnamesForm', {
            namaGudang: editData.nama_gudang || '',
            dataWarehousesId: editData.data_warehouses_id || '',

            updateDataWarehouse() {
                const gudang = window.dropdownData?.find(g => g.value === this.namaGudang);
                this.dataWarehousesId = gudang?.id || '';
            },
        });

        // ✅ Store untuk Material Search dengan data edit langsung
        Alpine.store('materialSearch', {
            jenisBm: editData.material_type || '',
            searchQuery: '',
            materials: [],
            loading: false,

            async fetchMaterials() {
                if (!this.jenisBm) {
                    this.materials = [];
                    return;
                }

                this.loading = true;

                try {
                    const params = new URLSearchParams({
                        jenis_bm: this.jenisBm,
                        search: this.searchQuery || ''
                    });

                    console.log('Fetching URL:',
                        `/admin/api/stock-opnames/materials?${params}`); // ✅ DEBUG

                    const response = await fetch(`/admin/api/stock-opnames/materials?${params}`);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();

                    this.materials = data; // ✅ Controller sudah return langsung array
                } catch (error) {
                    console.error('Error fetching materials:', error);
                    this.materials = [];
                } finally {
                    this.loading = false;
                }
            },

            selectMaterial(material) {
                window.selectMaterial(material, this.jenisBm);
            }
        });

        // ✅ AUTO-FETCH jika ada material_type di edit data
        if (editData.material_type) {
            setTimeout(() => {
                Alpine.store('materialSearch').fetchMaterials();
            }, 500); // Beri waktu untuk Alpine store ter-inisialisasi
        }
    });

    function stockOpnamesForm() {
        return {
            // Bind ke store materialSearch
            get jenisBm() {
                return Alpine.store('materialSearch').jenisBm
            },
            set jenisBm(value) {
                Alpine.store('materialSearch').jenisBm = value;
                Alpine.store('materialSearch').fetchMaterials();
            },

            get searchQuery() {
                return Alpine.store('materialSearch').searchQuery
            },
            set searchQuery(value) {
                Alpine.store('materialSearch').searchQuery = value;
                Alpine.store('materialSearch').fetchMaterials();
            },

            get materials() {
                return Alpine.store('materialSearch').materials
            },
            get loading() {
                return Alpine.store('materialSearch').loading
            },

            selectMaterial(material) {
                return Alpine.store('materialSearch').selectMaterial(material);
            },

            // ✅ Tambahkan method untuk fetch manual
            fetchMaterials() {
                return Alpine.store('materialSearch').fetchMaterials();
            },

            // Bind ke store opnamesForm
            get namaGudang() {
                return Alpine.store('opnamesForm').namaGudang
            },
            set namaGudang(value) {
                Alpine.store('opnamesForm').namaGudang = value;
                Alpine.store('opnamesForm').updateDataWarehouse();
            },

            get dataWarehousesId() {
                return Alpine.store('opnamesForm').dataWarehousesId
            },
            set dataWarehousesId(value) {
                Alpine.store('opnamesForm').dataWarehousesId = value
            },
        }
    }

    // Fungsi untuk select material dari tabel
    window.selectMaterial = function(material, jenisBm) {
        document.getElementById('material_type').value = jenisBm;
        document.getElementById('material_id').value = material.id;

        if (jenisBm === 'App\\Models\\IncomingRawMaterial') {
            // BM Baku: kode_barcode, nama_barang_detail, qty_roll, satuan_ukur
            document.getElementById('kode_item').value = material.kode_barcode;
            document.getElementById('kode_barcode').value = material.kode_barcode;
            document.getElementById('nama_item').value = material.nama_barang_detail;
            document.getElementById('satuan').value = material.satuan_ukur;
            document.getElementById('buku').value = material.qty_roll;
        } else if (jenisBm === 'App\\Models\\IncomingComplementMaterial') {
            // BM Pelengkap: kode, nama_barang_sesuai_nota, jumlah_sus, satuan_ukur_sus
            document.getElementById('kode_item').value = material.kode;
            document.getElementById('kode_barcode').value = material.kode;
            document.getElementById('nama_item').value = material.nama_barang_sesuai_nota;
            document.getElementById('satuan').value = material.satuan_ukur_sus;
            document.getElementById('buku').value = material.jumlah_sus;
        }

        // Trigger perhitungan selisih
        calculateSelisih();
    }

    // Fungsi untuk menghitung selisih
    function calculateSelisih() {
        const buku = parseFloat(document.getElementById('buku').value) || 0;
        const fisik = parseFloat(document.getElementById('fisik').value) || 0;
        const selisih = Math.max(0, buku - fisik); // Minimal 0, tidak boleh minus
        document.getElementById('selisih').value = selisih;
    }

    document.addEventListener('DOMContentLoaded', async function() {
        const jenisBmSelect = document.getElementById('jenis_bm');
        if (jenisBmSelect) {
            const tomSelect = new TomSelect(jenisBmSelect, {
                create: false,
                placeholder: "-- Pilih Jenis BM --",
                searchField: ["text"],
                maxOptions: null,
            });
            addCloseAnimation(tomSelect);
        }

        if (typeof TomSelect === 'undefined') {
            await waitForTomSelect();
        }

        // RENDER SKELETON DULU
        renderSkeletonDropdowns();

        // TUNGGU DATA AWAL SELESAI DIMUAT
        const initialData = await preloadInitialData();

        // BARU INISIALISASI TOMSELECT
        initializeTomSelectWithSearch(initialData);

        // ✅ Set nilai awal untuk edit mode
        setTimeout(() => {
            setEditValues(initialData);
        }, 800); // Tambah delay untuk memastikan semua ter-inisialisasi
    });

    // ✅ Fungsi untuk set nilai edit
    function setEditValues(data) {
        // Set nilai gudang
        const gudangSelect = document.getElementById('nama_gudang')?.tomselect;
        if (gudangSelect && editData.nama_gudang) {
            setTimeout(() => {
                if (gudangSelect.options[editData.nama_gudang]) {
                    gudangSelect.setValue(editData.nama_gudang, true);
                }
            }, 100);
        }

        // ✅ Set nilai jenis BM - pastikan select element ter-update
        if (editData.material_type) {
            const jenisBmSelect = document.getElementById('jenis_bm');
            if (jenisBmSelect?.tomselect) {
                // Jika menggunakan TomSelect
                setTimeout(() => {
                    jenisBmSelect.tomselect.setValue(editData.material_type, true);
                }, 100);
            } else if (jenisBmSelect) {
                // Jika select biasa
                jenisBmSelect.value = editData.material_type;

                // Trigger change event untuk Alpine
                const event = new Event('change', {
                    bubbles: true
                });
                jenisBmSelect.dispatchEvent(event);
            }
        }
    }

    function renderSkeletonDropdowns() {
        const dropdowns = ['nama_gudang'];
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
            const response = await fetch('/admin/api/stock-opnames/data');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            window.dropdownData = data; // Simpan ke global

            return data; // Kembalikan data
        } catch (error) {
            console.error('Preload data error:', error);
            window.dropdownData = [];
            return [];
        }
    }

    let lastRequestTime = 0;
    let searchTimeout;

    function initializeTomSelectWithSearch(initialData) {
        const config = {
            id: 'nama_gudang',
            placeholder: '-- Pilih Nama Gudang --'
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

            onInitialize() {
                if (Array.isArray(initialData)) {
                    initialData.forEach(item => this.addOption(item));
                    this.refreshOptions(false);
                }
            },

            load(query, callback) {
                if (query.length < 2) return callback();
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const now = Date.now();
                    if (now - lastRequestTime < 1000) {
                        console.log('Too frequent, using cached data');
                        return callback([]);
                    }

                    fetch(
                            `/admin/api/stock-opnames/data?q=${encodeURIComponent(query)}`
                        )
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                }, 300);
            },

            onChange(value) {
                const store = Alpine.store('opnamesForm');
                store.namaGudang = value;
                store.updateDataWarehouse();

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
