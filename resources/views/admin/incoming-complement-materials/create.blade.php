<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Input Bahan Pelengkap') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('incoming-complement-materials.store') }}"
                        x-data="incomingComplementMaterialForm()">
                        @csrf

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Tanggal Nota -->
                            <div>
                                <x-input-label for="tanggal_nota" :value="__('Tanggal Nota')" />
                                <x-text-input id="tanggal_nota" class="block mt-1 w-full" type="date"
                                    name="tanggal_nota" :value="old('tanggal_nota')" required />
                                <x-input-error :messages="$errors->get('tanggal_nota')" class="mt-2" />
                            </div>

                            <!-- No Kwitansi -->
                            <div>
                                <x-input-label for="no_kwitansi" :value="__('No Kwitansi')" />
                                <x-text-input id="no_kwitansi" class="block mt-1 w-full" type="text"
                                    name="no_kwitansi" :value="old('no_kwitansi')" required />
                                <x-input-error :messages="$errors->get('no_kwitansi')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Nama Supplier -->
                            <div class="select-search">
                                <x-input-label for="id_nama_supplier" :value="__('Nama Supplier')" />
                                <select id="id_nama_supplier" name="id_nama_supplier">
                                </select>
                                <x-input-error :messages="$errors->get('id_nama_supplier')" class="mt-2" />
                                <input type="hidden" name="nama_supplier" id="nama_supplier"
                                    x-model="$store.complementForm.namaSupplier" />
                            </div>

                            <!-- Kode Supplier -->
                            <div>
                                <x-input-label for="kode_supplier" :value="__('Kode Supplier')" />
                                <x-text-input id="kode_supplier" class="block mt-1 w-full" type="text"
                                    placeholder="Terisi Otomatis" name="kode_supplier"
                                    x-model="$store.complementForm.kodeSupplier" required readonly />
                                <x-input-error :messages="$errors->get('kode_supplier')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Kode -->
                            <div>
                                <x-input-label for="kode" :value="__('Kode')" />
                                <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode"
                                    placeholder="Terisi Otomatis" x-model="$store.complementForm.kode" readonly
                                    required />
                                <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                            </div>

                            <!-- Nama Barang Sesuai Nota -->
                            <div>
                                <x-input-label for="nama_barang_sesuai_nota" :value="__('Nama Barang Sesuai Nota')" />
                                <x-text-input id="nama_barang_sesuai_nota" class="block mt-1 w-full" type="text"
                                    name="nama_barang_sesuai_nota" required />
                                <x-input-error :messages="$errors->get('nama_barang_sesuai_nota')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Jenis -->
                            <div class="select-search">
                                <x-input-label for="database_materials_id" :value="__('Jenis')" />
                                <select name="database_materials_id" id="database_materials_id"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('database_materials_id')" class="mt-2" />
                            </div>

                            {{-- input tersembunyi untuk database materials --}}
                            <input type="hidden" id="jenis" name="jenis" x-model="$store.complementForm.jenis">

                            <!-- Jumlah SUS -->
                            <div>
                                <x-input-label for="jumlah_sus" :value="__('Jumlah SUS')" />
                                <x-text-input id="jumlah_sus" class="block mt-1 w-full" type="number" name="jumlah_sus"
                                    x-model.number="$store.complementForm.jumlahSUS"
                                    x-on:input="$store.complementForm.calculateTotalNilaiSI()" min="0"
                                    required />
                                <x-input-error :messages="$errors->get('jumlah_sus')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Satuan Ukur SUS -->
                            <div class="select-search">
                                <x-input-label for="id_satuan_ukur_sus" :value="__('Satuan Ukur SUS')" />
                                <select name="id_satuan_ukur_sus" id="id_satuan_ukur_sus"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('id_satuan_ukur_sus')" class="mt-2" />
                                <input type="hidden" name="satuan_ukur_sus" id="satuan_ukur_sus"
                                    x-model="$store.complementForm.satuanUkurSUS" />
                            </div>

                            <!-- Harga Satuan SUS -->
                            <div>
                                <x-input-label for="harga_satuan_sus" :value="__('Harga Satuan SUS')" />
                                <x-text-input id="harga_satuan_sus" class="block mt-1 w-full" type="number"
                                    x-model.number="$store.complementForm.hargaSatuanSUS"
                                    x-on:input="$store.complementForm.calculateHargaSatuanUkurSI()"
                                    name="harga_satuan_sus" min="0" step="0.01" required />
                                <x-input-error :messages="$errors->get('harga_satuan_sus')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Jumlah KSU -->
                            <div class="select-search">
                                <x-input-label for="unit_internals_id" :value="__('Jumlah KSU')" />
                                <select name="unit_internals_id" id="unit_internals_id"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('unit_internals_id')" class="mt-2" />
                                <input type="hidden" name="jumlah_ksu" id="jumlah_ksu"
                                    x-model="$store.complementForm.jumlahKSU" />
                            </div>

                            <!-- Satuan Ukur KSU -->
                            <div>
                                <x-input-label for="satuan_ukur_ksu" :value="__('Satuan Ukur KSU')" />
                                <x-text-input id="satuan_ukur_ksu" class="block mt-1 w-full" type="text"
                                    placeholder="Terisi Otomatis" x-model="$store.complementForm.satuanUkurKSU"
                                    readonly name="satuan_ukur_ksu" required />
                                <x-input-error :messages="$errors->get('satuan_ukur_ksu')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Total Nilai Satuan Ukuran Internal -->
                            <div>
                                <x-input-label for="total_nilai_si" :value="__('Total Nilai Satuan Ukuran Internal')" />
                                <x-text-input id="total_nilai_si" class="block mt-1 w-full" type="number"
                                    name="total_nilai_si" placeholder="Terisi Otomatis"
                                    x-model.number="$store.complementForm.totalNilaiSI" readonly required
                                    min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('total_nilai_si')" class="mt-2" />
                            </div>

                            <!-- Satuan Ukur SI -->
                            <div>
                                <x-input-label for="satuan_ukur_si" :value="__('Satuan Ukur SI')" />
                                <x-text-input id="satuan_ukur_si" class="block mt-1 w-full" type="text"
                                    name="satuan_ukur_si" x-model="$store.complementForm.satuanUkurSI" readonly
                                    placeholder="Terisi Otomatis" required />
                                <x-input-error :messages="$errors->get('satuan_ukur_si')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Harga/Satuan Ukur SI --}}
                            <div>
                                <x-input-label for="harga_satuan_ukur_si" :value="__('Harga/Satuan Ukur SI')" />
                                <x-text-input id="harga_satuan_ukur_si" class="block mt-1 w-full" type="number"
                                    x-model="hargaSatuanUkurSI" placeholder="Terisi Otomatis" readonly
                                    x-on:input="calculateSubTotal()" name="harga_satuan_ukur_si" min="0"
                                    step="0.01" required />
                                <x-input-error :messages="$errors->get('harga_satuan_ukur_si')" class="mt-2" />
                            </div>
                            <!-- Sub Total -->
                            <div>
                                <x-input-label for="sub_total" :value="__('Sub Total')" />
                                <x-text-input id="sub_total" class="block mt-1 w-full" type="number"
                                    name="sub_total" required placeholder="Terisi Otomatis" readonly
                                    x-model="subTotal" min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('sub_total')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('incoming-complement-materials.index') }}"
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
    // Buat store global untuk shared state
    document.addEventListener('alpine:init', () => {
        Alpine.store('complementForm', {
            idNamaSupplier: '',
            idSatuanUkurSUS: '',
            namaSupplier: '',
            kodeSupplier: '',
            jenis: '',
            kode: '',
            databaseMaterialsId: '',
            jumlahKSU: '',
            unitInternalsId: '',
            jumlahSUS: '',
            totalNilaiSI: '',
            hargaSatuanSUS: '',
            hargaSatuanUkurSI: '',
            subTotal: '',

            // Update methods
            updateKodeSupplier() {
                const supplier = window.dropdownData?.suppliers.find(s => s.value === this
                    .idNamaSupplier);
                this.namaSupplier = supplier?.nama_supplier || '';
                this.kodeSupplier = supplier?.kode || '';
            },

            updateKode() {
                const material = window.dropdownData?.materials.find(m => m.value == this
                    .databaseMaterialsId);
                if (material) {
                    this.jenis = material.name;
                    this.kode = material.kode_bahan;
                }
            },

            updateSatuanUkurSUS() {
                const measure = window.dropdownData?.measures.find(m => m.value === this
                    .idSatuanUkurSUS);
                this.satuanUkurSUS = measure?.satuan;
            },

            updateSatuanUkur() {
                this.satuanUkurKSU = this.satuanUkurSUS;
                this.satuanUkurSI = this.satuanUkurSUS;
            },

            updateInternalID() {
                const unit = window.dropdownData?.units.find(u => u.value == this.unitInternalsId);
                if (unit) {
                    this.jumlahKSU = unit.nilai;
                }
            },

            calculateTotalNilaiSI() {
                const sus = parseFloat(this.jumlahSUS) || 0;
                const ksu = parseFloat(this.jumlahKSU) || 0;
                this.totalNilaiSI = Math.round(sus * ksu * 100) / 100;
                this.calculateHargaSatuanUkurSI();
            },

            calculateHargaSatuanUkurSI() {
                const hargaSUS = parseFloat(this.hargaSatuanSUS) || 0;
                const jumlahSUS = parseFloat(this.jumlahSUS) || 0;
                const totalNilai = parseFloat(this.totalNilaiSI) || 1;

                const result = totalNilai ? (hargaSUS * jumlahSUS) / totalNilai : 0;
                this.hargaSatuanUkurSI = Math.round(result * 100) / 100;
                this.calculateSubTotal();
            },

            calculateSubTotal() {
                const hargaUkurSI = parseFloat(this.hargaSatuanUkurSI) || 0;
                const totalNilai = parseFloat(this.totalNilaiSI) || 0;
                this.subTotal = Math.round(hargaUkurSI * totalNilai * 100) / 100;
            }
        });
    });

    function incomingComplementMaterialForm() {
        return {
            // Gunakan store sebagai reactive data
            get namaSupplier() {
                return Alpine.store('complementForm').namaSupplier
            },
            set namaSupplier(value) {
                Alpine.store('complementForm').namaSupplier = value
            },

            get kodeSupplier() {
                return Alpine.store('complementForm').kodeSupplier
            },
            set kodeSupplier(value) {
                Alpine.store('complementForm').kodeSupplier = value
            },

            get jenis() {
                return Alpine.store('complementForm').jenis
            },
            set jenis(value) {
                Alpine.store('complementForm').jenis = value
            },

            get kode() {
                return Alpine.store('complementForm').kode
            },
            set kode(value) {
                Alpine.store('complementForm').kode = value
            },

            get databaseMaterialsId() {
                return Alpine.store('complementForm').databaseMaterialsId
            },
            set databaseMaterialsId(value) {
                Alpine.store('complementForm').databaseMaterialsId = value
            },

            get jumlahKSU() {
                return Alpine.store('complementForm').jumlahKSU
            },
            set jumlahKSU(value) {
                Alpine.store('complementForm').jumlahKSU = value
            },

            get unitInternalsId() {
                return Alpine.store('complementForm').unitInternalsId
            },
            set unitInternalsId(value) {
                Alpine.store('complementForm').unitInternalsId = value
            },

            get jumlahSUS() {
                return Alpine.store('complementForm').jumlahSUS
            },
            set jumlahSUS(value) {
                Alpine.store('complementForm').jumlahSUS = value
            },

            get totalNilaiSI() {
                return Alpine.store('complementForm').totalNilaiSI
            },
            set totalNilaiSI(value) {
                Alpine.store('complementForm').totalNilaiSI = value
            },

            get hargaSatuanSUS() {
                return Alpine.store('complementForm').hargaSatuanSUS
            },
            set hargaSatuanSUS(value) {
                Alpine.store('complementForm').hargaSatuanSUS = value
            },

            get hargaSatuanUkurSI() {
                return Alpine.store('complementForm').hargaSatuanUkurSI
            },
            set hargaSatuanUkurSI(value) {
                Alpine.store('complementForm').hargaSatuanUkurSI = value
            },

            get subTotal() {
                return Alpine.store('complementForm').subTotal
            },
            set subTotal(value) {
                Alpine.store('complementForm').subTotal = value
            },
        };
    }

    document.addEventListener('DOMContentLoaded', async function() {
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
        const dropdowns = ['id_nama_supplier', 'database_materials_id', 'unit_internals_id', 'id_satuan_ukur_sus'];
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
            const response = await fetch('/admin/api/incoming-complement-materials/data');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            window.dropdownData = data; // Simpan ke global

            return data; // Kembalikan data
        } catch (error) {
            console.error('Error preloading initial ', error);
            const emptyData = {
                suppliers: [],
                materials: [],
                measures: [],
                units: []
            };
            window.dropdownData = emptyData;
            return emptyData;
        }
    }

    let lastRequestTime = 0;
    let searchTimeout;

    function initializeTomSelectWithSearch(initialData) {
        const configs = [{
                id: 'id_nama_supplier',
                type: 'suppliers',
                placeholder: '-- Pilih Nama Supplier --'
            },
            {
                id: 'database_materials_id',
                type: 'materials',
                placeholder: '-- Pilih Jenis --'
            },
            {
                id: 'unit_internals_id',
                type: 'units',
                placeholder: '-- Pilih Jumlah KSU --'
            },
            {
                id: 'id_satuan_ukur_sus',
                type: 'measures',
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
                                `/admin/api/incoming-complement-materials/data?q=${encodeURIComponent(query)}`
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
                    const store = Alpine.store('complementForm');

                    switch (config.id) {
                        case 'id_nama_supplier':
                            store.idNamaSupplier = value;
                            store.updateKodeSupplier();
                            break;
                        case 'database_materials_id':
                            store.databaseMaterialsId = value;
                            store.updateKode();
                            break;
                        case 'unit_internals_id':
                            store.unitInternalsId = value;
                            store.updateInternalID();
                            store.calculateTotalNilaiSI();
                            break;
                        case 'id_satuan_ukur_sus':
                            store.idSatuanUkurSUS = value;
                            store.updateSatuanUkurSUS();
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
