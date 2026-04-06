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
                    <form method="POST" action="{{ route('stock-raw-materials.update', $stockRawMaterial->id) }}"
                        x-data="stockRawMaterialsForm()">
                        @csrf
                        @method('PUT')

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
                            x-on:input="$store.stockRmForm.updateAllField()" step="0.01" :value="old('keluar_yard', $stockRawMaterial->keluar_yard)" />

                        {{-- Jika ada stock opname --}}
                        @if ($stockOpnames)
                            <div class="select-search">
                                <x-input-label for="stock_opnames_id" :value="__('Pilih Stock Opname')" />
                                <select name="stock_opnames_id" id="stock_opnames_id"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer"></select>
                                <x-input-error :messages="$errors->get('stock_opnames_id')" class="mt-2" />
                            </div>
                        @endif

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Keluar Roll --}}
                            <div>
                                <x-input-label for="keluar_roll" :value="__('Keluar Roll')" />
                                <x-text-input id="keluar_roll" class="block mt-1 w-full" type="number"
                                    x-model="$store.stockRmForm.keluarRoll" name="keluar_roll" required readonly
                                    placeholder="Terisi Otomatis" step="0.01" :value="old('keluar_roll', $stockRawMaterial->keluar_roll)" />
                                <x-input-error :messages="$errors->get('keluar_roll')" class="mt-2" />
                            </div>

                            {{-- Stock Akhir --}}
                            <div>
                                <x-input-label for="stock_akhir">
                                    Stock Akhir
                                    {{-- cek apakah stock opname ada, jika ada maka buat tag span dengan isi "Dari Stock Opname", jika tidak ada maka "Dari BM Baku" --}}
                                    <span class="text-indigo-600">
                                        {{ $stockOpnames ? '(Dari Stock Opname)' : '(Dari BM Baku)' }}
                                    </span>
                                </x-input-label>
                                <x-text-input id="stock_akhir" class="block mt-1 w-full" type="number"
                                    name="stock_akhir" required readonly placeholder="Terisi Otomatis"
                                    x-model="$store.stockRmForm.stockAkhir" step="0.01" :value="old('stock_akhir', $stockRawMaterial->stock_akhir)" />
                                <x-input-error :messages="$errors->get('stock_akhir')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Sisa Roll --}}
                            <div>
                                <x-input-label for="sisa_roll" :value="__('Sisa Roll')" />
                                <x-text-input id="sisa_roll" class="block mt-1 w-full" type="number" name="sisa_roll"
                                    required readonly placeholder="Terisi Otomatis"
                                    x-model="$store.stockRmForm.sisaRoll" step="0.01" :value="old('sisa_roll', $stockRawMaterial->sisa_roll)" />
                                <x-input-error :messages="$errors->get('sisa_roll')" class="mt-2" />
                            </div>

                            {{-- Sisa Yard --}}
                            <div>
                                <x-input-label for="sisa_yard" :value="__('Sisa Yard')" />
                                <x-text-input id="sisa_yard" class="block mt-1 w-full" type="number" name="sisa_yard"
                                    required readonly placeholder="Terisi Otomatis"
                                    x-model="$store.stockRmForm.sisaYard" step="0.01" :value="old('sisa_yard', $stockRawMaterial->sisa_yard)" />
                                <x-input-error :messages="$errors->get('sisa_yard')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Total Harga --}}
                            <div>
                                <x-input-label for="total_harga" :value="__('Total Harga')" />
                                <x-text-input id="total_harga" class="block mt-1 w-full" type="number"
                                    name="total_harga" required step="0.01" readonly placeholder="Terisi Otomatis"
                                    x-model.number="$store.stockRmForm.totalHarga" :value="old('total_harga', $stockRawMaterial->total_harga)" />
                                <x-input-error :messages="$errors->get('total_harga')" class="mt-2" />
                            </div>

                            {{-- Harga Per Satuan --}}
                            <div>
                                <x-input-label for="harga_per_satuan" :value="__('Harga Per Satuan')" />
                                <x-text-input id="harga_per_satuan" class="block mt-1 w-full" type="number"
                                    name="harga_per_satuan" required step="0.01" readonly
                                    placeholder="Terisi Otomatis" x-model.number="$store.stockRmForm.hargaPerSatuan"
                                    :value="old('harga_per_satuan', $stockRawMaterial->harga_per_satuan)" />
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
    const hasStockOpname = {{ $stockOpnames ? 'true' : 'false' }};

    const editData = {
        incoming_raw_materials_id: "{{ $stockRawMaterial->incoming_raw_materials_id }}",
        keluar_yard: "{{ $stockRawMaterial->keluar_yard }}",
        stock_opnames_id: "{{ $stockRawMaterial->stock_opnames_id }}",
        keluar_roll: "{{ $stockRawMaterial->keluar_roll }}",
        stock_akhir: "{{ $stockRawMaterial->stock_akhir }}",
        sisa_roll: "{{ $stockRawMaterial->sisa_roll }}",
        sisa_yard: "{{ $stockRawMaterial->sisa_yard }}",
        total_harga: "{{ $stockRawMaterial->total_harga }}",
        harga_per_satuan: "{{ $stockRawMaterial->harga_per_satuan }}",
    };

    document.addEventListener('alpine:init', () => {
        Alpine.store('stockRmForm', {
            incomingRawMaterialsId: editData.incoming_raw_materials_id,
            keluarYard: editData.keluar_yard,
            keluarRoll: editData.keluar_roll,
            stockOpnamesId: editData.stock_opnames_id,
            stockAkhir: editData.stock_akhir,
            sisaRoll: editData.sisa_roll,
            sisaYard: editData.sisa_yard,
            totalHarga: editData.total_harga,
            hargaPerSatuan: editData.harga_per_satuan,

            updateAllField() {
                // Pastikan keluarYard adalah angka
                const keluarYard = parseFloat(this.keluarYard) || 0;

                const selected = window.dropdownData?.incomingRawMaterials?.find(
                    n => n.value == this.incomingRawMaterialsId
                );

                if (!selected) {
                    console.log('No raw material selected');
                    return;
                }

                // Validasi data dari API
                const yard = parseFloat(selected.yard) || 0;
                const qtyRoll = parseFloat(selected.qty_roll) || 0;
                const jumlahRollSatuan = parseFloat(selected.jumlah_roll_satuan) || 0;
                const hargaPerSatuan = parseFloat(selected.harga_per_satuan) || 0;

                // Hitung keluar roll
                this.keluarRoll = Math.round((keluarYard - yard) * 100) / 100;

                // Jangan auto-fill stockAkhir jika ada stock opname
                // Stock akhir hanya diisi dari stock opname yang dipilih user
                // Jika tidak ada stock opname di sistem, baru gunakan qty_roll
                if (!hasStockOpname) {
                    // Tidak ada stock opname sama sekali, gunakan qty_roll
                    this.stockAkhir = qtyRoll;
                }
                // Jika stockOpnames = true, biarkan stockAkhir kosong sampai user pilih

                // Hitung sisa
                this.sisaYard = Math.max(0, Math.round((jumlahRollSatuan - keluarYard) * 100) / 100);
                this.sisaRoll = yard > 0 ? Math.round((this.sisaYard / yard) * 100) / 100 : 0;

                // Hitung harga
                this.totalHarga = Math.round(this.sisaYard * hargaPerSatuan * 100) / 100;
                this.hargaPerSatuan = (this.sisaYard === 0) ?
                    0 :
                    Math.round((this.totalHarga / this.sisaYard) * 100) / 100;

            },
            updateStockAkhir() {
                const selected = window.dropdownData?.stockOpnames?.find(
                    n => n.value == this.stockOpnamesId
                );

                if (selected) {
                    this.stockAkhir = parseFloat(selected.fisik) || 0;
                    // Recalculate setelah update stock akhir
                    if (this.incomingRawMaterialsId && this.keluarYard > 0) {
                        this.updateAllField();
                    }
                }
            }
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

        setInitialValuesFromInline();
    });

    function renderSkeletonDropdowns() {
        const dropdowns = ['incoming_raw_materials_id', 'stock_opnames_id'];
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

    // ⚠️ SETEL NILAI DARI JSON INLINE
    function setInitialValuesFromInline() {
        const configs = [{
            id: 'incoming_raw_materials_id',
            value: editData.incoming_raw_materials_id
        }, ];

        // Tambahkan stock_opnames_id hanya jika ada data
        if (hasStockOpname) {
            configs.push({
                id: 'stock_opnames_id',
                value: editData.stock_opnames_id
            });
        }

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
            id: 'incoming_raw_materials_id',
            type: 'incomingRawMaterials',
            placeholder: '-- Pilih Bahan Baku --'
        }, ];

        // Tambahkan stock_opnames_id hanya jika ada data
        if (hasStockOpname) {
            configs.push({
                id: 'stock_opnames_id',
                type: 'stockOpnames',
                placeholder: '-- Pilih Stock Opname --'
            });
        }

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
                    // Gunakan data yang sudah dipreload
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
                                `/admin/api/stock-raw-materials/data?q=${encodeURIComponent(query)}`
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
                    const store = Alpine.store('stockRmForm');

                    switch (config.id) {
                        case 'incoming_raw_materials_id':
                            store.incomingRawMaterialsId = value;
                            store.updateAllField();
                            break;
                        case 'stock_opnames_id':
                            store.stockOpnamesId = value;
                            store.updateStockAkhir();
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
