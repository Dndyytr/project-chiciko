<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pembelian Berdasarkan Roll') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('purchase-based-rolls.store') }}" x-data="purchaseBasedRollsForm()">
                        @csrf

                        <!-- Pilih Roll -->
                        <div class="my-4 select-search">
                            <x-input-label for="incoming_raw_materials_id" :value="__('Pilih Roll')" />
                            <select name="incoming_raw_materials_id" id="incoming_raw_materials_id"
                                class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                            </select>
                            <x-input-error :messages="$errors->get('incoming_raw_materials_id')" class="mt-2" />
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Kode Barcode -->
                            <div>
                                <x-input-label for="kode_barcode" :value="__('Kode Barcode')" />
                                <x-text-input id="kode_barcode" class="block mt-1 w-full" type="text"
                                    name="kode_barcode" x-model="$store.rollsForm.kodeBarcode"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('kode_barcode')" class="mt-2" />
                            </div>

                            <!-- Nama Barang -->
                            <div>
                                <x-input-label for="nama_barang" :value="__('Nama Barang')" />
                                <x-text-input id="nama_barang" class="block mt-1 w-full" type="text"
                                    name="nama_barang" x-model="$store.rollsForm.namaBarang"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('nama_barang')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Jenis Kain -->
                            <div>
                                <x-input-label for="jenis_kain" :value="__('Jenis Kain')" />
                                <x-text-input id="jenis_kain" class="block mt-1 w-full" type="text" name="jenis_kain"
                                    x-model="$store.rollsForm.jenisKain" placeholder="Terisi Otomatis" readonly
                                    required />
                                <x-input-error :messages="$errors->get('jenis_kain')" class="mt-2" />
                            </div>

                            <!-- Warna -->
                            <div>
                                <x-input-label for="warna" :value="__('Warna')" />
                                <x-text-input id="warna" class="block mt-1 w-full" type="text" name="warna"
                                    x-model="$store.rollsForm.warna" placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('warna')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- QTY Roll -->
                            <div>
                                <x-input-label for="qty_roll" :value="__('QTY Roll')" />
                                <x-text-input id="qty_roll" class="block mt-1 w-full" type="number" name="qty_roll"
                                    x-model.number="$store.rollsForm.qtyRoll" placeholder="Terisi Otomatis" readonly
                                    required />
                                <x-input-error :messages="$errors->get('qty_roll')" class="mt-2" />
                            </div>

                            <!-- Yard Per Roll -->
                            <div>
                                <x-input-label for="yard_per_roll" :value="__('Yard Per Roll')" />
                                <x-text-input id="yard_per_roll" class="block mt-1 w-full" type="number"
                                    name="yard_per_roll" required />
                                <x-input-error :messages="$errors->get('yard_per_roll')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid w-full grid-cols-2 gap-2 lg:gap-4">
                            <!-- Kg Per Roll -->
                            <div>
                                <x-input-label for="kg_per_roll" :value="__('Kg Per Roll')" />
                                <x-text-input id="kg_per_roll" class="block mt-1 w-full" type="number" step="0.01"
                                    name="kg_per_roll" required />
                                <x-input-error :messages="$errors->get('kg_per_roll')" class="mt-2" />
                            </div>
                            <!-- Jumlah Roll Satuan -->
                            <div>
                                <x-input-label for="jumlah_roll_satuan" :value="__('Jumlah Roll X Satuan')" />
                                <x-text-input id="jumlah_roll_satuan" class="block mt-1 w-full" type="number"
                                    name="jumlah_roll_satuan" x-model="$store.rollsForm.jumlahRollSatuan" readonly
                                    placeholder="Terisi Otomatis" required />
                                <x-input-error :messages="$errors->get('jumlah_roll_satuan')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid w-full grid-cols-2 gap-2 lg:gap-4">
                            <!-- Total Harga -->
                            <div>
                                <x-input-label for="total_harga" :value="__('Total Harga')" />
                                <x-text-input id="total_harga" class="block mt-1 w-full" type="number" step="0.01"
                                    name="total_harga" x-model="$store.rollsForm.totalHarga" readonly
                                    placeholder="Terisi Otomatis" required />
                                <x-input-error :messages="$errors->get('total_harga')" class="mt-2" />
                            </div>
                            <!-- Harga Per Satuan -->
                            <div>
                                <x-input-label for="harga_per_satuan" :value="__('Harga Per Satuan')" />
                                <x-text-input id="harga_per_satuan" class="block mt-1 w-full" type="number"
                                    step="0.01" name="harga_per_satuan" x-model="$store.rollsForm.hargaPerSatuan"
                                    readonly placeholder="Terisi Otomatis" required />
                                <x-input-error :messages="$errors->get('harga_per_satuan')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('purchase-based-rolls.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
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
        Alpine.store('rollsForm', {
            rollSelector: '',
            kodeBarcode: '',
            namaBarang: '',
            jenisKain: '',
            warna: '',
            qtyRoll: '',
            jumlahRollSatuan: '',
            totalHarga: '',
            hargaPerSatuan: '',

            updateAllField() {
                const selected = window.dropdownData?.find(n => n.value == this.rollSelector);
                if (selected) {
                    this.kodeBarcode = selected.kode_barcode;
                    this.namaBarang = selected.nama_barang;
                    this.jenisKain = selected.jenis_kain;
                    this.warna = selected.warna;
                    this.qtyRoll = parseFloat(selected.qty_roll) || 0;
                    this.jumlahRollSatuan = parseFloat(selected.jumlah_roll_satuan) || 0;
                    this.totalHarga = parseFloat(selected.total_harga) || 0
                }
                console.log(this.rollSelector);
            },
            hargaPersatuan() {
                const totalHarga = parseFloat(this.totalHarga) || 0;
                const jumlahRollSatuan = parseFloat(this.jumlahRollSatuan) || 1;
                this.hargaPerSatuan = Math.max(0, Math.round(totalHarga / jumlahRollSatuan) *
                    100) / 100;
            },
        });
    })

    function purchaseBasedRollsForm() {
        return {
            get rollSelector() {
                return Alpine.store('rollsForm').rollSelector;
            },
            set rollSelector(value) {
                Alpine.store('rollsForm').rollSelector = value;
            },

            get kodeBarcode() {
                return Alpine.store('rollsForm').kodeBarcode;
            },
            set kodeBarcode(value) {
                return Alpine.store('rollsForm').kodeBarcode = value;
            },

            get namaBarang() {
                return Alpine.store('rollsForm').namaBarang;
            },
            set namaBarang(value) {
                return Alpine.store('rollsForm').namaBarang = value;
            },

            get jenisKain() {
                return Alpine.store('rollsForm').jenisKain;
            },
            set jenisKain(value) {
                return Alpine.store('rollsForm').jenisKain = value;
            },
            get warna() {
                return Alpine.store('rollsForm').warna;
            },
            set warna(value) {
                return Alpine.store('rollsForm').warna = value;
            },

            get qtyRoll() {
                return Alpine.store('rollsForm').qtyRoll;
            },
            set qtyRoll(value) {
                return Alpine.store('rollsForm').qtyRoll = value;
            },

            get jumlahRollSatuan() {
                return Alpine.store('rollsForm').jumlahRollSatuan;
            },
            set jumlahRollSatuan(value) {
                return Alpine.store('rollsForm').jumlahRollSatuan = value;
            },

            get totalHarga() {
                return Alpine.store('rollsForm').totalHarga;
            },
            set totalHarga(value) {
                return Alpine.store('rollsForm').totalHarga = value;
            },

            get hargaPerSatuan() {
                return Alpine.store('rollsForm').hargaPerSatuan;
            },
            set hargaPerSatuan(value) {
                return Alpine.store('rollsForm').hargaPerSatuan = value;
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
            const response = await fetch('/admin/api/purchase-based-rolls/data');
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
            placeholder: '-- Pilih Roll --'
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
                            `/admin/api/purchase-based-rolls/data?q=${encodeURIComponent(query)}`
                        )
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                    // console.log(`/admin/api/purchase-based-rolls/data?q=${encodeURIComponent(query)}`);
                }, 300);
            },

            onChange(value) {
                const store = Alpine.store('rollsForm');
                store.rollSelector = value;
                store.updateAllField();
                store.hargaPersatuan();
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
