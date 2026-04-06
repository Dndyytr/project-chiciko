<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah SP Bahan Pelengkap') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('stock-complement-materials.store') }}" x-data="stockComplementMaterialsForm()">
                        @csrf
                        <!-- Pilih Bahan Pelengkap -->
                        <div class="select-search">
                            <x-input-label for="incoming_complement_materials_id" :value="__('Pilih Bahan Pelengkap')" />
                            <select name="incoming_complement_materials_id" id="incoming_complement_materials_id"
                                class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer"></select>
                            <x-input-error :messages="$errors->get('incoming_complement_materials_id')" class="mt-2" />
                        </div>

                        {{-- Barang Keluar --}}
                        <x-input-label for="barang_keluar" :value="__('Barang Keluar')" />
                        <x-text-input id="barang_keluar" class="block mt-1 w-full" type="number" name="barang_keluar"
                            required x-model="$store.stockCmForm.barangKeluar"
                            x-on:input="$store.stockCmForm.updateAllField()" step="0.01" />

                        <input type="hidden" name="stock_opnames_id" id="stock_opnames_id"
                            x-model="$store.stockCmForm.stockOpnamesId">
                        <input type="hidden" name="nama_item" id="nama_item" x-model="$store.stockCmForm.namaItem">

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Stock Akhir --}}
                            <div>
                                <x-input-label for="stock_akhir" :value="__('Stock Akhir')" />
                                <x-text-input id="stock_akhir" class="block mt-1 w-full" type="number"
                                    name="stock_akhir" required readonly placeholder="Terisi Otomatis"
                                    x-model="$store.stockCmForm.stockAkhir" step="0.01" />
                                <x-input-error :messages="$errors->get('stock_akhir')" class="mt-2" />
                            </div>

                            {{-- Harga Barang Keluar --}}
                            <div>
                                <x-input-label for="harga_barang_keluar" :value="__('Harga Barang Keluar')" />
                                <x-text-input id="harga_barang_keluar" class="block mt-1 w-full" type="number"
                                    name="harga_barang_keluar" required step="0.01" readonly
                                    placeholder="Terisi Otomatis"
                                    x-model.number="$store.stockCmForm.hargaBarangKeluar" />
                                <x-input-error :messages="$errors->get('harga_barang_keluar')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Total Harga Stock Akhir --}}
                            <div>
                                <x-input-label for="total_harga_stock_akhir" :value="__('Total Harga Stock Akhir')" />
                                <x-text-input id="total_harga_stock_akhir" class="block mt-1 w-full" type="number"
                                    name="total_harga_stock_akhir" required step="0.01" readonly
                                    placeholder="Terisi Otomatis"
                                    x-model.number="$store.stockCmForm.totalHargaStockAkhir" />
                                <x-input-error :messages="$errors->get('total_harga_stock_akhir')" class="mt-2" />
                            </div>

                            <div>
                                {{-- Harga Satuan Stock Akhir --}}
                                <x-input-label for="harga_satuan_stock_akhir" :value="__('Harga Satuan Stock Akhir')" />
                                <x-text-input id="harga_satuan_stock_akhir" class="block mt-1 w-full" type="number"
                                    name="harga_satuan_stock_akhir" required step="0.01" readonly
                                    placeholder="Terisi Otomatis"
                                    x-model.number="$store.stockCmForm.hargaSatuanStockAkhir" />
                                <x-input-error :messages="$errors->get('harga_satuan_stock_akhir')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('stock-complement-materials.index') }}"
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
        Alpine.store('stockCmForm', {
            incomingComplementMaterialsId: '',
            namaItem: '',
            barangKeluar: '',
            stockOpnamesId: '',
            stockAkhir: '',
            hargaBarangKeluar: '',
            totalHargaStockAkhir: '',
            hargaSatuanStockAkhir: '',

            updateAllField() {
                // Pastikan barangKeluar adalah angka
                const barangKeluar = parseFloat(this.barangKeluar) || 0;

                const selected = window.dropdownData?.find(
                    n => n.value == this.incomingComplementMaterialsId
                );

                if (!selected) {
                    // console.log('No complement material selected');
                    return;
                }

                this.stockOpnamesId = selected.stock_opnames_id;
                this.namaItem = selected.nama_barang_sesuai_nota;

                // Validasi data dari API
                const totalNilaiSI = parseFloat(selected.total_nilai_si) || 0;
                const hargaSatuanUkurSI = parseFloat(selected.harga_satuan_ukur_si) || 0;

                this.stockAkhir = totalNilaiSI;

                // Hitung Harga Barang Keluar
                this.hargaBarangKeluar = hargaSatuanUkurSI * barangKeluar;

                // Hitung Total Harga Stock Akhir
                this.totalHargaStockAkhir = hargaSatuanUkurSI > 0 ? Math.round((hargaSatuanUkurSI * this
                        .stockAkhir) * 100) / 100 :
                    0;

                // Hitung Harga Satuan Stock Akhir
                this.hargaSatuanStockAkhir = (this.stockAkhir === 0) ?
                    0 :
                    Math.round((this.totalHargaStockAkhir / this.stockAkhir) * 100) / 100;

            },
        });
    })

    function stockComplementMaterialsForm() {
        return {
            get incomingComplementMaterialsId() {
                return Alpine.store('stockCmForm').incomingComplementMaterialsId;
            },
            set incomingComplementMaterialsId(value) {
                return Alpine.store('stockCmForm').incomingComplementMaterialsId = value;
            },

            get namaItem() {
                return Alpine.store('stockCmForm').namaItem;
            },
            set namaItem(value) {
                return Alpine.store('stockCmForm').namaItem = value;
            },

            get stockOpnamesId() {
                return Alpine.store('stockCmForm').stockOpnamesId;
            },

            set stockOpnamesId(value) {
                return Alpine.store('stockCmForm').stockOpnamesId = value;
            },

            get barangKeluar() {
                return Alpine.store('stockCmForm').barangKeluar;
            },
            set barangKeluar(value) {
                return Alpine.store('stockCmForm').barangKeluar = value;
            },

            get stockAkhir() {
                return Alpine.store('stockCmForm').stockAkhir;
            },
            set stockAkhir(value) {
                return Alpine.store('stockCmForm').stockAkhir = value;
            },

            get hargaBarangKeluar() {
                return Alpine.store('stockCmForm').hargaBarangKeluar;
            },
            set hargaBarangKeluar(value) {
                return Alpine.store('stockCmForm').hargaBarangKeluar = value;
            },

            get totalHargaStockAkhir() {
                return Alpine.store('stockCmForm').totalHargaStockAkhir;
            },
            set totalHargaStockAkhir(value) {
                return Alpine.store('stockCmForm').totalHargaStockAkhir = value;
            },

            get hargaSatuanStockAkhir() {
                return Alpine.store('stockCmForm').hargaSatuanStockAkhir;
            },
            set hargaSatuanStockAkhir(value) {
                return Alpine.store('stockCmForm').hargaSatuanStockAkhir = value;
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
        const dropdowns = ['incoming_complement_materials_id'];
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
            const response = await fetch('/admin/api/stock-complement-materials/data');
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
            id: 'incoming_complement_materials_id',
            type: 'incomingRawMaterials',
            placeholder: '-- Pilih Bahan Pelengkap --'
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
                            `/admin/api/stock-complement-materials/data?q=${encodeURIComponent(query)}`
                        )
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                }, 300);
            },

            onChange(value) {
                // ⚠️ GUNAKAN STORE LANGSUNG, BUKAN COMPONENT
                const store = Alpine.store('stockCmForm');
                store.incomingComplementMaterialsId = value;
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
