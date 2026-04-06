<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pembelian Berdasarkan Nama Bahan') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('complement-based-materials.store') }}" x-data="complementBasedMaterialsForm()"
                        x-init="loadOldValues()">
                        @csrf

                        <!-- Pilih Nama Barang Sesuai Nota -->
                        <div class="my-4 select-search">
                            <x-input-label for="complement_materials_id" :value="__('Pilih Nama Barang Sesuai Nota')" />
                            <select name="complement_materials_id" id="complement_materials_id"
                                class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                            </select>
                            <x-input-error :messages="$errors->get('complement_materials_id')" class="mt-2" />
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Nama Barang Sesuai Nota -->
                            <div>
                                <x-input-label for="nama_barang_sesuai_nota" :value="__('Nama Barang Sesuai Nota')" />
                                <x-text-input id="nama_barang_sesuai_nota" class="block mt-1 w-full" type="text"
                                    name="nama_barang_sesuai_nota" x-model="$store.materialsForm.namaBarangSesuaiNota"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('nama_barang_sesuai_nota')" class="mt-2" />
                            </div>


                            <!-- Jumlah SUS -->
                            <div>
                                <x-input-label for="jumlah_sus" :value="__('Jumlah SUS')" />
                                <x-text-input id="jumlah_sus" class="block mt-1 w-full" type="number" step="0.01"
                                    min="0" name="jumlah_sus" x-model.number="$store.materialsForm.jumlahSUS"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('jumlah_sus')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Satuan Ukur SUS -->
                            <div>
                                <x-input-label for="satuan_ukur_sus" :value="__('Satuan Ukur SUS')" />
                                <x-text-input id="satuan_ukur_sus" class="block mt-1 w-full" type="text"
                                    name="satuan_ukur_sus" x-model="$store.materialsForm.satuanUkurSUS"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('satuan_ukur_sus')" class="mt-2" />
                            </div>

                            <!-- Harga Satuan SUS -->
                            <div>
                                <x-input-label for="harga_satuan_sus" :value="__('Harga Satuan SUS')" />
                                <x-text-input id="harga_satuan_sus" class="block mt-1 w-full" type="number"
                                    step="0.01" min="0" name="harga_satuan_sus"
                                    x-model="$store.materialsForm.hargaSatuanSUS" placeholder="Terisi Otomatis" readonly
                                    required />
                                <x-input-error :messages="$errors->get('harga_satuan_sus')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Jumlah KSU -->
                            <div>
                                <x-input-label for="jumlah_ksu" :value="__('Jumlah KSU')" />
                                <x-text-input id="jumlah_ksu" class="block mt-1 w-full" type="number" step="0.01"
                                    min="0" name="jumlah_ksu" x-model="$store.materialsForm.jumlahKSU"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('jumlah_ksu')" class="mt-2" />
                            </div>

                            <!-- Satuan Ukur KSU -->
                            <div>
                                <x-input-label for="satuan_ukur_ksu" :value="__('Satuan Ukur KSU')" />
                                <x-text-input id="satuan_ukur_ksu" class="block mt-1 w-full" type="text"
                                    name="satuan_ukur_ksu" x-model="$store.materialsForm.satuanUkurKSU"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('satuan_ukur_ksu')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Total Nilai SI -->
                            <div>
                                <x-input-label for="total_nilai_si" :value="__('Total Nilai SI')" />
                                <x-text-input id="total_nilai_si" class="block mt-1 w-full" type="number"
                                    step="0.01" min="0" name="total_nilai_si"
                                    x-model="$store.materialsForm.totalNilaiSI" placeholder="Terisi Otomatis" readonly
                                    required />
                                <x-input-error :messages="$errors->get('total_nilai_si')" class="mt-2" />
                            </div>

                            <!-- Satuan Ukur SI -->
                            <div>
                                <x-input-label for="satuan_ukur_si" :value="__('Satuan Ukur SI')" />
                                <x-text-input id="satuan_ukur_si" class="block mt-1 w-full" type="text"
                                    name="satuan_ukur_si" x-model="$store.materialsForm.satuanUkurSI"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('satuan_ukur_si')" class="mt-2" />
                            </div>
                        </div>
                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Harga Satuan SI -->
                            <div>
                                <x-input-label for="harga_satuan_ukur_si" :value="__('Harga Satuan SI')" />
                                <x-text-input id="harga_satuan_ukur_si" class="block mt-1 w-full" type="number"
                                    step="0.01" min="0" name="harga_satuan_ukur_si"
                                    x-model="$store.materialsForm.hargaSatuanUkurSI" placeholder="Terisi Otomatis"
                                    readonly required />
                                <x-input-error :messages="$errors->get('harga_satuan_ukur_si')" class="mt-2" />
                            </div>

                            <!-- Sub Total -->
                            <div>
                                <x-input-label for="sub_total" :value="__('Sub Total')" />
                                <x-text-input id="sub_total" class="block mt-1 w-full" type="number" step="0.01"
                                    name="sub_total" x-model="$store.materialsForm.subTotal"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('total')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('complement-based-materials.index') }}"
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
        Alpine.store('materialsForm', {
            namaSelector: '',
            namaBarangSesuaiNota: '',
            jumlahSUS: '',
            satuanUkurSUS: '',
            hargaSatuanSUS: '',
            jumlahKSU: '',
            satuanUkurKSU: '',
            totalNilaiSI: '',
            satuanUkurSI: '',
            hargaSatuanUkurSI: '',
            subTotal: '',

            updateAllField() {
                const selected = window.dropdownData?.find(n => n.value == this.namaSelector);
                if (selected) {
                    this.namaBarangSesuaiNota = selected.nama_barang_sesuai_nota;
                    this.jumlahSUS = parseFloat(selected.jumlah_sus) || 0;
                    this.satuanUkurSUS = selected.satuan_ukur_sus;
                    this.hargaSatuanSUS = parseFloat(selected.harga_satuan_sus) || 0;
                    this.jumlahKSU = parseFloat(selected.jumlah_ksu) || 0;
                    this.satuanUkurKSU = selected.satuan_ukur_ksu;
                    this.totalNilaiSI = parseFloat(selected.total_nilai_si) || 0
                    this.satuanUkurSI = selected.satuan_ukur_si;
                    this.hargaSatuanUkurSI = parseFloat(selected.harga_satuan_ukur_si) || 0;
                    this.subTotal = parseFloat(selected.sub_total) || 0;
                }
            },

        });
    })


    function complementBasedMaterialsForm() {
        return {
            get namaSelector() {
                return Alpine.store('materialsForm').namaSelector;
            },
            set namaSelector(value) {
                Alpine.store('materialsForm').namaSelector = value;
            },

            get namaBarangSesuaiNota() {
                return Alpine.store('materialsForm').namaBarangSesuaiNota;
            },
            set namaBarangSesuaiNota(value) {
                return Alpine.store('materialsForm').namaBarangSesuaiNota = value;
            },

            get jumlahSUS() {
                return Alpine.store('materialsForm').jumlahSUS;
            },
            set jumlahSUS(value) {
                return Alpine.store('materialsForm').jumlahSUS = value;
            },

            get satuanUkurSUS() {
                return Alpine.store('materialsForm').satuanUkurSUS;
            },
            set satuanUkurSUS(value) {
                return Alpine.store('materialsForm').satuanUkurSUS = value;
            },

            get hargaSatuanSUS() {
                return Alpine.store('materialsForm').hargaSatuanSUS;
            },
            set hargaSatuanSUS(value) {
                return Alpine.store('materialsForm').hargaSatuanSUS = value;
            },

            get jumlahKSU() {
                return Alpine.store('materialsForm').jumlahKSU;
            },
            set jumlahKSU(value) {
                return Alpine.store('materialsForm').jumlahKSU = value;
            },

            get satuanUkurKSU() {
                return Alpine.store('materialsForm').satuanUkurKSU;
            },
            set satuanUkurKSU(value) {
                return Alpine.store('materialsForm').satuanUkurKSU = value;
            },

            get totalNilaiSI() {
                return Alpine.store('materialsForm').totalNilaiSI;
            },
            set totalNilaiSI(value) {
                return Alpine.store('materialsForm').totalNilaiSI = value;
            },

            get satuanUkurSI() {
                return Alpine.store('materialsForm').satuanUkurSI;
            },
            set satuanUkurSI(value) {
                return Alpine.store('materialsForm').satuanUkurSI = value;
            },

            get hargaSatuanUkurSI() {
                return Alpine.store('materialsForm').hargaSatuanUkurSI;
            },
            set hargaSatuanUkurSI(value) {
                return Alpine.store('materialsForm').hargaSatuanUkurSI = value;
            },

            get subTotal() {
                return Alpine.store('materialsForm').subTotal;
            },
            set subTotal(value) {
                return Alpine.store('materialsForm').subTotal = value;
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
        const dropdowns = ['complement_materials_id'];
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
            const response = await fetch('/admin/api/complement-based-materials/data');
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
            id: 'complement_materials_id',
            placeholder: '-- Pilih Nota Pembelian --'
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
                            `/admin/api/complement-based-materials/data?q=${encodeURIComponent(query)}`
                        )
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                    // console.log(`/admin/api/complement-based-materials/data?q=${encodeURIComponent(query)}`);
                }, 300);
            },

            onChange(value) {
                const store = Alpine.store('materialsForm');
                store.namaSelector = value;
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
