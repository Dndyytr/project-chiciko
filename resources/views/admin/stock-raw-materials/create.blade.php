<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah SP Bahan Baku') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('stock-raw-materials.store') }}" x-data="stockRawMaterialsForm()">
                        @csrf
                        <!-- Pilih Bahan Baku -->
                        <div class="select-search">
                            <x-input-label for="incoming_raw_materials_id" :value="__('Pilih Bahan Baku')" />
                            <select name="incoming_raw_materials_id" id="incoming_raw_materials_id"
                                class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer"></select>
                            <x-input-error :messages="$errors->get('incoming_raw_materials_id')" class="mt-2" />
                        </div>

                        {{-- Keluar Yard --}}
                        <x-input-label for="keluar_yard" :value="__('Keluar Yard')" />
                        <x-text-input id="keluar_yard" class="block mt-1 w-full" type="number" name="keluar_yard"
                            required x-model="$store.stockRmForm.keluarYard"
                            x-on:input="$store.stockRmForm.updateAllField()" step="0.01" />

                        <input type="hidden" name="stock_opnames_id" id="stock_opnames_id"
                            x-model="$store.stockRmForm.stockOpnamesId">
                        <input type="hidden" name="nama_item" id="nama_item" x-model="$store.stockRmForm.namaItem">

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Keluar Roll --}}
                            <div>
                                <x-input-label for="keluar_roll" :value="__('Keluar Roll')" />
                                <x-text-input id="keluar_roll" class="block mt-1 w-full" type="number"
                                    x-model="$store.stockRmForm.keluarRoll" name="keluar_roll" required readonly
                                    placeholder="Terisi Otomatis" step="0.01" />
                                <x-input-error :messages="$errors->get('keluar_roll')" class="mt-2" />
                            </div>

                            {{-- Stock Akhir --}}
                            <div>
                                <x-input-label for="stock_akhir" :value="__('Stock Akhir')" />
                                <x-text-input id="stock_akhir" class="block mt-1 w-full" type="number"
                                    name="stock_akhir" required readonly placeholder="Terisi Otomatis"
                                    x-model="$store.stockRmForm.stockAkhir" step="0.01" />
                                <x-input-error :messages="$errors->get('stock_akhir')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Sisa Roll --}}
                            <div>
                                <x-input-label for="sisa_roll" :value="__('Sisa Roll')" />
                                <x-text-input id="sisa_roll" class="block mt-1 w-full" type="number" name="sisa_roll"
                                    required readonly placeholder="Terisi Otomatis"
                                    x-model="$store.stockRmForm.sisaRoll" step="0.01" />
                                <x-input-error :messages="$errors->get('sisa_roll')" class="mt-2" />
                            </div>

                            {{-- Sisa Yard --}}
                            <div>
                                <x-input-label for="sisa_yard" :value="__('Sisa Yard')" />
                                <x-text-input id="sisa_yard" class="block mt-1 w-full" type="number" name="sisa_yard"
                                    required readonly placeholder="Terisi Otomatis"
                                    x-model="$store.stockRmForm.sisaYard" step="0.01" />
                                <x-input-error :messages="$errors->get('sisa_yard')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Total Harga --}}
                            <div>
                                <x-input-label for="total_harga" :value="__('Total Harga')" />
                                <x-text-input id="total_harga" class="block mt-1 w-full" type="number"
                                    name="total_harga" required step="0.01" readonly placeholder="Terisi Otomatis"
                                    x-model.number="$store.stockRmForm.totalHarga" />
                                <x-input-error :messages="$errors->get('total_harga')" class="mt-2" />
                            </div>

                            {{-- Harga Per Satuan --}}
                            <div>
                                <x-input-label for="harga_per_satuan" :value="__('Harga Per Satuan')" />
                                <x-text-input id="harga_per_satuan" class="block mt-1 w-full" type="number"
                                    name="harga_per_satuan" required step="0.01" readonly
                                    placeholder="Terisi Otomatis" x-model.number="$store.stockRmForm.hargaPerSatuan" />
                                <x-input-error :messages="$errors->get('harga_per_satuan')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('stock-raw-materials.index') }}"
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
    document.addEventListener('alpine:init', () => {
        Alpine.store('stockRmForm', {
            incomingRawMaterialsId: '',
            namaItem: '',
            keluarYard: '',
            keluarRoll: '',
            stockOpnamesId: '',
            stockAkhir: '',
            sisaRoll: '',
            sisaYard: '',
            totalHarga: '',
            hargaPerSatuan: '',

            updateAllField() {
                // Pastikan keluarYard adalah angka
                const keluarYard = parseFloat(this.keluarYard) || 0;

                const selected = window.dropdownData?.find(
                    n => n.value == this.incomingRawMaterialsId
                );

                if (!selected) {
                    // console.log('No raw material selected');
                    return;
                }

                this.stockOpnamesId = selected.stock_opnames_id;
                this.namaItem = selected.nama_barang_detail;

                // Validasi data dari API
                const yard = parseFloat(selected.yard) || 0;
                const qtyRoll = parseFloat(selected.qty_roll) || 0;
                const jumlahRollSatuan = parseFloat(selected.jumlah_roll_satuan) || 0;
                const hargaPerSatuan = parseFloat(selected.harga_per_satuan) || 0;

                // Hitung keluar roll
                this.keluarRoll = keluarYard - yard;

                this.stockAkhir = qtyRoll;

                // Hitung sisa
                this.sisaYard = jumlahRollSatuan - keluarYard;
                this.sisaRoll = yard > 0 ? Math.round((this.sisaYard / yard) * 100000) / 100000 : 0;

                // Hitung harga
                this.totalHarga = Math.round(this.sisaYard * hargaPerSatuan * 100) / 100;
                this.hargaPerSatuan = (this.sisaYard === 0) ?
                    0 :
                    Math.round((this.totalHarga / this.sisaYard) * 100) / 100;

            },
        });
    })

    function stockRawMaterialsForm() {
        return {
            get incomingRawMaterialsId() {
                return Alpine.store('stockRmForm').incomingRawMaterialsId;
            },
            set incomingRawMaterialsId(value) {
                return Alpine.store('stockRmForm').incomingRawMaterialsId = value;
            },

            get namaItem() {
                return Alpine.store('stockRmForm').namaItem;
            },
            set namaItem(value) {
                return Alpine.store('stockRmForm').namaItem = value;
            },

            get stockOpnamesId() {
                return Alpine.store('stockRmForm').stockOpnamesId;
            },

            set stockOpnamesId(value) {
                return Alpine.store('stockRmForm').stockOpnamesId = value;
            },

            get keluarYard() {
                return Alpine.store('stockRmForm').keluarYard;
            },
            set keluarYard(value) {
                return Alpine.store('stockRmForm').keluarYard = value;
            },
            get keluarRoll() {
                return Alpine.store('stockRmForm').keluarRoll;
            },
            set keluarRoll(value) {
                return Alpine.store('stockRmForm').keluarRoll = value;
            },

            get stockAkhir() {
                return Alpine.store('stockRmForm').stockAkhir;
            },
            set stockAkhir(value) {
                return Alpine.store('stockRmForm').stockAkhir = value;
            },

            get sisaRoll() {
                return Alpine.store('stockRmForm').sisaRoll;
            },
            set sisaRoll(value) {
                return Alpine.store('stockRmForm').sisaRoll = value;
            },

            get sisaYard() {
                return Alpine.store('stockRmForm').sisaYard;
            },
            set sisaYard(value) {
                return Alpine.store('stockRmForm').sisaYard = value;
            },

            get totalHarga() {
                return Alpine.store('stockRmForm').totalHarga;
            },
            set totalHarga(value) {
                return Alpine.store('stockRmForm').totalHarga = value;
            },

            get hargaPerSatuan() {
                return Alpine.store('stockRmForm').hargaPerSatuan;
            },
            set hargaPerSatuan(value) {
                return Alpine.store('stockRmForm').hargaPerSatuan = value;
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
        const dropdowns = ['incoming_raw_materials_id'];
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
            const response = await fetch('/admin/api/stock-raw-materials/data');
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
            id: 'incoming_raw_materials_id',
            type: 'incomingRawMaterials',
            placeholder: '-- Pilih Bahan Baku --'
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
            onInitialize: function() {
                // Gunakan data yang sudah dipreload
                if (Array.isArray(initialData)) {
                    initialData.forEach(item => this.addOption(item));
                    this.refreshOptions(false);
                }
            },

            load(query, callback) {
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
                            `/admin/api/stock-raw-materials/data?q=${encodeURIComponent(query)}`
                        )
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                }, 300);
            },

            onChange(value) {
                // ⚠️ GUNAKAN STORE LANGSUNG, BUKAN COMPONENT
                const store = Alpine.store('stockRmForm');
                store.incomingRawMaterialsId = value;
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
