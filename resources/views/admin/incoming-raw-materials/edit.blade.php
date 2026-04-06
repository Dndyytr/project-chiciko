<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Bahan Masuk') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('incoming-raw-materials.update', $incomingRawMaterial->id) }}"
                        x-data="incomingRawMaterialForm()">
                        @csrf
                        @method('PUT')

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Tanggal Nota -->
                            <div>
                                <x-input-label for="tanggal_nota" :value="__('Tanggal Nota')" />
                                <x-text-input id="tanggal_nota" class="block mt-1 w-full" type="date"
                                    name="tanggal_nota" :value="old('tanggal_nota', $incomingRawMaterial->tanggal_nota)" required />
                                <x-input-error :messages="$errors->get('tanggal_nota')" class="mt-2" />
                            </div>

                            <!-- No Kwitansi -->
                            <div>
                                <x-input-label for="no_kwitansi" :value="__('No Kwitansi')" />
                                <x-text-input id="no_kwitansi" class="block mt-1 w-full" type="text"
                                    name="no_kwitansi" :value="old('no_kwitansi', $incomingRawMaterial->no_kwitansi)" required />
                                <x-input-error :messages="$errors->get('no_kwitansi')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Nama Supplier -->
                            <div class="select-search">
                                <x-input-label for="id_nama_supplier" :value="__('Nama Supplier')" />
                                <select name="id_nama_supplier" id="id_nama_supplier"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_nama_supplier')" class="mt-2" />
                                <input type="hidden" name="nama_supplier" id="nama_supplier"
                                    x-model="$store.rawMaterialForm.namaSupplier"
                                    value="{{ old('nama_supplier', $incomingRawMaterial->nama_supplier) }}" />
                            </div>

                            <!-- Kode Supplier -->
                            <div>
                                <x-input-label for="kode_supplier" :value="__('Kode Supplier')" />
                                <x-text-input id="kode_supplier" class="block mt-1 w-full" type="text"
                                    placeholder="Terisi Otomatis" name="kode_supplier" :value="old('kode_supplier', $incomingRawMaterial->kode_supplier)"
                                    x-model="$store.rawMaterialForm.kodeSupplier" required readonly />
                                <x-input-error :messages="$errors->get('kode_supplier')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Kode Barcode -->
                            <div>
                                <x-input-label for="kode_barcode" :value="__('Kode Barcode')" />
                                <x-text-input id="kode_barcode" class="block mt-1 w-full" type="text"
                                    :value="old('kode_barcode', $incomingRawMaterial->kode_barcode)" name="kode_barcode" required />
                                <x-input-error :messages="$errors->get('kode_barcode')" class="mt-2" />
                            </div>

                            {{-- satuan ukur --}}
                            <div class="select-search">
                                <x-input-label for="id_satuan_ukur" :value="__('Satuan Ukur')" />
                                <select name="id_satuan_ukur" id="id_satuan_ukur"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_satuan_ukur')" class="mt-2" />
                                <input type="hidden" name="satuan_ukur" id="satuan_ukur"
                                    x-model="$store.rawMaterialForm.satuanUkur"
                                    value="{{ old('satuan_ukur', $incomingRawMaterial->satuan_ukur) }}" />
                            </div>

                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Nama Barang -->
                            <div>
                                <x-input-label for="nama_barang" :value="__('Nama Barang')" />
                                <x-text-input id="nama_barang" class="block mt-1 w-full" type="text"
                                    name="nama_barang" x-model="namaBarang" :value="old('nama_barang', $incomingRawMaterial->nama_barang)" required />
                                <x-input-error :messages="$errors->get('nama_barang')" class="mt-2" />
                            </div>

                            <!-- Jenis Kain -->
                            <div>
                                <x-input-label for="jenis_kain" :value="__('Jenis Kain')" />
                                <x-text-input id="jenis_kain" class="block mt-1 w-full" type="text" name="jenis_kain"
                                    x-model="jenisKain" :value="old('jenis_kain', $incomingRawMaterial->jenis_kain)" required />
                                <x-input-error :messages="$errors->get('jenis_kain')" class="mt-2" />
                            </div>

                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Warna -->
                            <div>
                                <x-input-label for="warna" :value="__('Warna')" />
                                <x-text-input id="warna" class="block mt-1 w-full" type="text" name="warna"
                                    x-model="warna" :value="old('warna', $incomingRawMaterial->warna)" required />
                                <x-input-error :messages="$errors->get('warna')" class="mt-2" />
                            </div>

                            <!-- Yard -->
                            <div>
                                <x-input-label for="yard" :value="__('Yard')" />
                                <x-text-input id="yard" class="block mt-1 w-full" type="number" name="yard"
                                    x-model="$store.rawMaterialForm.yard"
                                    x-on:input="$store.rawMaterialForm.calculateAll()" required min="0"
                                    :value="old('yard', $incomingRawMaterial->yard)" step="0.01" />
                                <x-input-error :messages="$errors->get('yard')" class="mt-2" />
                            </div>

                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Nama Barang Detail -->
                            <div>
                                <x-input-label for="nama_barang_detail" :value="__('Nama Barang Detail')" />
                                <x-text-input id="nama_barang_detail" class="block mt-1 w-full" type="text"
                                    placeholder="Terisi Otomatis" name="nama_barang_detail" :value="old('nama_barang_detail', $incomingRawMaterial->nama_barang_detail)"
                                    x-model="$store.rawMaterialForm.namaBarangDetail" readonly required />
                                <x-input-error :messages="$errors->get('nama_barang_detail')" class="mt-2" />
                            </div>

                            <!-- QTY Roll -->
                            <div>
                                <x-input-label for="qty_roll" :value="__('QTY Roll')" />
                                <x-text-input id="qty_roll" class="block mt-1 w-full" type="number"
                                    name="qty_roll" x-model.number="$store.rawMaterialForm.qtyRoll" :value="old('qty_roll', $incomingRawMaterial->qty_roll)"
                                    x-on:input="$store.rawMaterialForm.calculateAll()" required min="0"
                                    step="1" />
                                <x-input-error :messages="$errors->get('qty_roll')" class="mt-2" />
                            </div>

                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Kg Per Roll -->
                            <div>
                                <x-input-label for="kg_roll" :value="__('Kg Per Roll')" />
                                <x-text-input id="kg_roll" class="block mt-1 w-full" type="number" name="kg_roll"
                                    :value="old('kg_roll', $incomingRawMaterial->kg_roll)" required min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('kg_roll')" class="mt-2" />
                            </div>

                            <!-- Jumlah Rol X Satuan -->
                            <div>
                                <x-input-label for="jumlah_roll_satuan" :value="__('Jumlah Rol X Satuan')" />
                                <x-text-input id="jumlah_roll_satuan" class="block mt-1 w-full" type="number"
                                    name="jumlah_roll_satuan" x-model.number="$store.rawMaterialForm.jumlahRollSatuan"
                                    :value="old('jumlah_roll_satuan', $incomingRawMaterial->jumlah_roll_satuan)" placeholder="Terisi Otomatis" readonly required
                                    step="0.01" />
                                <x-input-error :messages="$errors->get('jumlah_roll_satuan')" class="mt-2" />
                            </div>

                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Harga Persatuan -->
                            <div>
                                <x-input-label for="harga_per_satuan" :value="__('Harga Persatuan')" />
                                <x-text-input id="harga_per_satuan" class="block mt-1 w-full" type="number"
                                    name="harga_per_satuan" x-model.number="$store.rawMaterialForm.hargaPersatuan"
                                    :value="old('harga_per_satuan', $incomingRawMaterial->harga_per_satuan)" x-on:input="$store.rawMaterialForm.calculateAll()" required
                                    min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('harga_per_satuan')" class="mt-2" />
                            </div>

                            <!-- Harga Awal -->
                            <div>
                                <x-input-label for="harga_awal" :value="__('Harga Awal')" />
                                <x-text-input id="harga_awal" class="block mt-1 w-full" type="number"
                                    name="harga_awal" x-model.number="$store.rawMaterialForm.hargaAwal" required
                                    :value="old('harga_awal', $incomingRawMaterial->harga_awal)" placeholder="Terisi Otomatis" readonly step="0.01" />
                                <x-input-error :messages="$errors->get('harga_awal')" class="mt-2" />
                            </div>

                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Nominal Diskon -->
                            <div>
                                <x-input-label for="nominal_diskon" :value="__('Nominal Diskon')" />
                                <x-text-input id="nominal_diskon" class="block mt-1 w-full" type="number"
                                    x-model.number="$store.rawMaterialForm.nominalDiskon" :value="old('nominal_diskon', $incomingRawMaterial->nominal_diskon)"
                                    x-on:input="$store.rawMaterialForm.calculateAll()" name="nominal_diskon" required
                                    min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('nominal_diskon')" class="mt-2" />
                            </div>

                            <!-- Total Diskon -->
                            <div>
                                <x-input-label for="total_diskon" :value="__('Total Diskon')" />
                                <x-text-input id="total_diskon" class="block mt-1 w-full" type="number"
                                    :value="old('total_diskon', $incomingRawMaterial->total_diskon)" placeholder="Terisi Otomatis" name="total_diskon"
                                    x-model="$store.rawMaterialForm.totalDiskon" required readonly step="0.01" />
                                <x-input-error :messages="$errors->get('total_diskon')" class="mt-2" />
                            </div>

                        </div>

                        <!-- Total Harga -->
                        <div>
                            <x-input-label for="total_harga" :value="__('Total Harga')" />
                            <x-text-input id="total_harga" class="block mt-1 w-full" type="number"
                                :value="old('total_harga', $incomingRawMaterial->total_harga)" placeholder="Terisi Otomatis" name="total_harga"
                                x-model="$store.rawMaterialForm.totalHarga" readonly required step="0.01" />
                            <x-input-error :messages="$errors->get('total_harga')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('incoming-raw-materials.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md
font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2
focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Back') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const editData = {
        nama_supplier: "{{ $incomingRawMaterial->nama_supplier }}",
        kode_supplier: "{{ $incomingRawMaterial->kode_supplier }}",
        nama_barang: "{{ $incomingRawMaterial->nama_barang }}",
        jenis_kain: "{{ $incomingRawMaterial->jenis_kain }}",
        warna: "{{ $incomingRawMaterial->warna }}",
        yard: "{{ $incomingRawMaterial->yard }}",
        qty_roll: "{{ $incomingRawMaterial->qty_roll }}",
        harga_persatuan: "{{ $incomingRawMaterial->harga_per_satuan }}",
        nominal_diskon: "{{ $incomingRawMaterial->nominal_diskon }}",
        nama_barang_detail: "{{ $incomingRawMaterial->nama_barang_detail }}",
        jumlah_roll_satuan: "{{ $incomingRawMaterial->jumlah_roll_satuan }}",
        harga_awal: "{{ $incomingRawMaterial->harga_awal }}",
        total_diskon: "{{ $incomingRawMaterial->total_diskon }}",
        total_harga: "{{ $incomingRawMaterial->total_harga }}",

        satuan_ukur: "{{ $incomingRawMaterial->satuan_ukur }}",
    };

    document.addEventListener('alpine:init', () => {
        Alpine.store('rawMaterialForm', {
            // Basic fields
            idNamaSupplier: '',
            idSatuanUkur: '',
            satuanUkur: editData.satuan_ukur,
            namaSupplier: editData.nama_supplier,
            kodeSupplier: editData.kode_supplier,
            namaBarang: editData.nama_barang,
            jenisKain: editData.jenis_kain,
            warna: editData.warna,
            yard: editData.yard,
            qtyRoll: editData.qty_roll,
            hargaPersatuan: editData.harga_persatuan,
            nominalDiskon: editData.nominal_diskon,

            // Computed fields ('' = belum dihitung)
            jumlahRollSatuan: editData.jumlah_roll_satuan,
            hargaAwal: editData.harga_awal,
            totalDiskon: editData.total_diskon,
            totalHarga: editData.total_harga,

            updateKodeSupplier() {
                const supplier = window.dropdownData?.listSupplierEstimates.find(s => s.value === this
                    .idNamaSupplier);
                this.namaSupplier = supplier?.nama_supplier || '';
                this.kodeSupplier = supplier?.kode || '';
            },

            updateSatuanUkur() {
                const measure = window.dropdownData?.listUnitMeasureEstimates.find(s => s.value === this
                    .idSatuanUkur);
                this.satuanUkur = measure?.satuan || '';
            },

            calculateAll() {
                this.calculateJumlahRollSatuan();
                this.calculateHargaAwal();
                this.calculateTotalDiskon();
                this.calculateTotalHarga();
            },

            calculateJumlahRollSatuan() {
                const yard = parseFloat(this.yard) || 0;
                const qtyRoll = parseFloat(this.qtyRoll) || 0;
                this.jumlahRollSatuan = Math.max(0, Math.round(yard * qtyRoll * 100) / 100);
            },

            calculateHargaAwal() {
                const jumlahRollSatuan = parseFloat(this.jumlahRollSatuan) || 0;
                const hargaPersatuan = parseFloat(this.hargaPersatuan) || 0;
                this.hargaAwal = Math.max(0, Math.round(jumlahRollSatuan * hargaPersatuan * 100) / 100);
            },

            calculateTotalDiskon() {
                const nominalDiskon = parseFloat(this.nominalDiskon) || 0;
                const jumlahRollSatuan = parseFloat(this.jumlahRollSatuan) || 0;
                this.totalDiskon = Math.max(0, Math.round(nominalDiskon * jumlahRollSatuan * 100) /
                    100);
            },

            calculateTotalHarga() {
                const hargaAwal = parseFloat(this.hargaAwal) || 0;
                const totalDiskon = parseFloat(this.totalDiskon) || 0;
                this.totalHarga = Math.max(0, Math.round((hargaAwal - totalDiskon) * 100) / 100);
            },

            get namaBarangDetail() {
                const parts = [
                    this.namaBarang,
                    this.jenisKain,
                    this.warna,
                    this.yard ? `${this.yard} YARD` : null
                ].filter(Boolean);

                return parts.join(' ');
            },

        });
    });

    // Supplier mapping data
    function incomingRawMaterialForm() {
        return {
            get satuanUkur() {
                return Alpine.store('rawMaterialForm').satuanUkur;
            },
            set satuanUkur(value) {
                Alpine.store('rawMaterialForm').satuanUkur = value;
            },

            get namaSupplier() {
                return Alpine.store('rawMaterialForm').namaSupplier;
            },
            set namaSupplier(value) {
                Alpine.store('rawMaterialForm').namaSupplier = value;
            },

            get kodeSupplier() {
                return Alpine.store('rawMaterialForm').kodeSupplier;
            },
            set kodeSupplier(value) {
                Alpine.store('rawMaterialForm').kodeSupplier = value;
            },

            get namaBarang() {
                return Alpine.store('rawMaterialForm').namaBarang;
            },
            set namaBarang(value) {
                Alpine.store('rawMaterialForm').namaBarang = value;
            },

            get jenisKain() {
                return Alpine.store('rawMaterialForm').jenisKain;
            },
            set jenisKain(value) {
                Alpine.store('rawMaterialForm').jenisKain = value;
            },

            get warna() {
                return Alpine.store('rawMaterialForm').warna;
            },
            set warna(value) {
                Alpine.store('rawMaterialForm').warna = value;
            },

            get yard() {
                return Alpine.store('rawMaterialForm').yard;
            },
            set yard(value) {
                Alpine.store('rawMaterialForm').yard = value;
            },

            get namaBarangDetail() {
                return Alpine.store('rawMaterialForm').namaBarangDetail;
            },
            set namaBarangDetail(value) {
                Alpine.store('rawMaterialForm').namaBarangDetail = value;
            },

            get qtyRoll() {
                return Alpine.store('rawMaterialForm').qtyRoll;
            },
            set qtyRoll(value) {
                Alpine.store('rawMaterialForm').qtyRoll = value;
            },

            get jumlahRollSatuan() {
                return Alpine.store('rawMaterialForm').jumlahRollSatuan;
            },
            set jumlahRollSatuan(value) {
                Alpine.store('rawMaterialForm').jumlahRollSatuan = value;
            },

            get hargaPersatuan() {
                return Alpine.store('rawMaterialForm').hargaPersatuan;
            },
            set hargaPersatuan(value) {
                Alpine.store('rawMaterialForm').hargaPersatuan = value;
            },

            get hargaAwal() {
                return Alpine.store('rawMaterialForm').hargaAwal;
            },
            set hargaAwal(value) {
                Alpine.store('rawMaterialForm').hargaAwal = value;
            },

            get nominalDiskon() {
                return Alpine.store('rawMaterialForm').nominalDiskon;
            },
            set nominalDiskon(value) {
                Alpine.store('rawMaterialForm').nominalDiskon = value;
            },

            get totalDiskon() {
                return Alpine.store('rawMaterialForm').totalDiskon;
            },
            set totalDiskon(value) {
                Alpine.store('rawMaterialForm').totalDiskon = value;
            },

            get totalHarga() {
                return Alpine.store('rawMaterialForm').totalHarga;
            },
            set totalHarga(value) {
                Alpine.store('rawMaterialForm').totalHarga = value;
            },
        };
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
        const dropdowns = ['id_nama_supplier', 'id_satuan_ukur'];
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
            const response = await fetch('/admin/api/incoming-raw-materials/data');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            window.dropdownData = data; // Simpan ke global

            return data; // Kembalikan data
        } catch (error) {
            console.error('Error fetching initial data:', error);
            throw error;
        }
    }

    // ✅ FUNGSI BARU: Cari ID dari text yang tersimpan
    function findIDsFromTexts() {
        const store = Alpine.store('rawMaterialForm');

        if (!window.dropdownData) return;

        // Find ID untuk Nama Supplier
        const namaSupplierItem = window.dropdownData.listSupplierEstimates.find(item =>
            item.nama_supplier === editData.nama_supplier && item.kode === editData.kode_supplier
        );
        if (namaSupplierItem) {
            store.idNamaSupplier = namaSupplierItem.value;
        }

        // Find ID untuk Satuan Ukur
        const satuanUkurItem = window.dropdownData.listUnitMeasureEstimates.find(item =>
            item.satuan === editData.satuan_ukur
        );
        if (satuanUkurItem) {
            store.idSatuanUkur = satuanUkurItem.value;
        }

    }

    // ⚠️ SETEL NILAI DARI JSON INLINE
    function setInitialValuesFromInline() {
        const store = Alpine.store('rawMaterialForm');

        const configs = [{
                id: 'id_nama_supplier',
                value: store.idNamaSupplier
            },
            {
                id: 'id_satuan_ukur',
                value: store.idSatuanUkur
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
                id: 'id_nama_supplier',
                type: 'listSupplierEstimates',
                placeholder: '-- Pilih Nama Supplier --'
            },
            {
                id: 'id_satuan_ukur',
                type: 'listUnitMeasureEstimates',
                placeholder: '-- Pilih Satuan Ukur --'
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
                                `/admin/api/incoming-raw-materials/data?q=${encodeURIComponent(query)}`
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
                    const store = Alpine.store('rawMaterialForm');

                    switch (config.id) {
                        case 'id_nama_supplier':
                            store.idNamaSupplier = value;
                            store.updateKodeSupplier();
                            break;
                        case 'id_satuan_ukur':
                            store.idSatuanUkur = value;
                            store.updateSatuanUkur();
                            break;
                    }
                }

            });
            addCloseAnimation(tomSelect);
        });

    }

    // Fungsi untuk menambahkan animasi close
    function addCloseAnimation(tomSelect) {
        let closeTimeout; //variabel close
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
            }, 400); //durasi select ditutup
        };
    }
</script>
