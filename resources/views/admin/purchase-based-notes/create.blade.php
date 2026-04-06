<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pembelian Berdasarkan Nota') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('purchase-based-notes.store') }}" x-data="purchaseBasedNotesForm()">
                        @csrf

                        <!-- Tanggal Nota -->
                        <div class="my-4 select-search">
                            <x-input-label for="nota_selector" :value="__('Pilih Nota')" />
                            <select name="nota_selector" id="nota_selector"
                                class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                            </select>
                            <x-input-error :messages="$errors->get('nota_selector')" class="mt-2" />
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Tanggal Nota -->
                            <div>
                                <x-input-label for="tanggal_nota" :value="__('Tanggal Nota')" />
                                <x-text-input id="tanggal_nota" class="block mt-1 w-full" type="date"
                                    name="tanggal_nota" x-model="$store.notesForm.tanggalNota"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('tanggal_nota')" class="mt-2" />
                            </div>

                            <!-- No Kwitansi -->
                            <div>
                                <x-input-label for="no_kwitansi" :value="__('No Kwitansi')" />
                                <x-text-input id="no_kwitansi" class="block mt-1 w-full" type="text"
                                    name="no_kwitansi" x-model="$store.notesForm.noKwitansi"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('no_kwitansi')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Kode Supplier -->
                            <div>
                                <x-input-label for="kode_supplier" :value="__('Kode Supplier')" />
                                <x-text-input id="kode_supplier" class="block mt-1 w-full" type="text"
                                    name="kode_supplier" x-model="$store.notesForm.kodeSupplier"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('kode_supplier')" class="mt-2" />
                            </div>

                            <!-- Nama Supplier -->
                            <div>
                                <x-input-label for="nama_supplier" :value="__('Nama Supplier')" />
                                <x-text-input id="nama_supplier" class="block mt-1 w-full" type="text"
                                    name="nama_supplier" x-model="$store.notesForm.namaSupplier"
                                    placeholder="Terisi Otomatis" readonly required />
                                <x-input-error :messages="$errors->get('nama_supplier')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- QTY Roll -->
                            <div>
                                <x-input-label for="qty_roll" :value="__('QTY Roll')" />
                                <x-text-input id="qty_roll" class="block mt-1 w-full" type="number" name="qty_roll"
                                    x-model.number="$store.notesForm.qtyRoll" placeholder="Terisi Otomatis" readonly
                                    required />
                                <x-input-error :messages="$errors->get('qty_roll')" class="mt-2" />
                            </div>

                            <!-- QTY Yard -->
                            <div>
                                <x-input-label for="qty_yard" :value="__('QTY Yard')" />
                                <x-text-input id="qty_yard" class="block mt-1 w-full" type="number" name="qty_yard"
                                    x-model.number="$store.notesForm.qtyYard" placeholder="Terisi Otomatis" readonly
                                    required />
                                <x-input-error :messages="$errors->get('qty_yard')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <x-input-label for="jumlah" :value="__('Jumlah')" />
                            <x-text-input id="jumlah" class="block mt-1 w-full" type="number" step="0.01"
                                name="jumlah" x-model.number="$store.notesForm.jumlah" placeholder="Terisi Otomatis"
                                readonly required />
                            <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('purchase-based-notes.index') }}"
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
        Alpine.store('notesForm', {
            notaSelector: '',
            tanggalNota: '',
            noKwitansi: '',
            kodeSupplier: '',
            namaSupplier: '',
            qtyRoll: '',
            qtyYard: '',
            jumlah: '',

            updateAllField() {
                const selected = window.dropdownData?.find(n => n.value === this.notaSelector);
                if (selected) {
                    this.tanggalNota = selected.tanggal_nota;
                    this.noKwitansi = selected.no_kwitansi;
                    this.kodeSupplier = selected.kode_supplier;
                    this.namaSupplier = selected.nama_supplier;
                    this.qtyRoll = parseFloat(selected.qty_roll) || 0;
                    this.qtyYard = parseFloat(selected.qty_yard) || 0;
                    this.jumlah = parseFloat(selected.jumlah) || 0;
                }
            },

        });
    })


    function purchaseBasedNotesForm() {
        return {
            get notaSelector() {
                return Alpine.store('notesForm').notaSelector;
            },
            set notaSelector(value) {
                Alpine.store('notesForm').notaSelector = value;
            },

            get tanggalNota() {
                return Alpine.store('notesForm').tanggalNota;
            },
            set tanggalNota(value) {
                return Alpine.store('notesForm').tanggalNota = value;
            },

            get noKwitansi() {
                return Alpine.store('notesForm').noKwitansi;
            },
            set noKwitansi(value) {
                return Alpine.store('notesForm').noKwitansi = value;
            },

            get kodeSupplier() {
                return Alpine.store('notesForm').kodeSupplier;
            },
            set kodeSupplier(value) {
                return Alpine.store('notesForm').kodeSupplier = value;
            },

            get namaSupplier() {
                return Alpine.store('notesForm').namaSupplier;
            },
            set namaSupplier(value) {
                return Alpine.store('notesForm').namaSupplier = value;
            },

            get qtyRoll() {
                return Alpine.store('notesForm').qtyRoll;
            },
            set qtyRoll(value) {
                return Alpine.store('notesForm').qtyRoll = value;
            },

            get qtyYard() {
                return Alpine.store('notesForm').qtyYard;
            },
            set qtyYard(value) {
                return Alpine.store('notesForm').qtyYard = value;
            },
            get jumlah() {
                return Alpine.store('notesForm').jumlah;
            },
            set jumlah(value) {
                return Alpine.store('notesForm').jumlah = value;
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
        const dropdowns = ['nota_selector'];
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
            const response = await fetch('/admin/api/purchase-based-notes/data');
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
            id: 'nota_selector',
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
                            `/admin/api/purchase-based-notes/data?q=${encodeURIComponent(query)}`
                        )
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                    // console.log(`/admin/api/purchase-based-notes/data?q=${encodeURIComponent(query)}`);
                }, 300);
            },

            onChange(value) {
                const store = Alpine.store('notesForm');
                store.notaSelector = value;
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
