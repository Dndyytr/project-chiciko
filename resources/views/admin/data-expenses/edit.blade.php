<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Data Pengeluaran') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('data-expenses.update', $dataExpense->id) }}"
                        x-data="dataExpensesForm()">
                        @csrf
                        @method('PUT')

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Tanggal Nota -->
                            <div>
                                <x-input-label for="tanggal_nota" :value="__('Tanggal Nota')" />
                                <x-text-input id="tanggal_nota" class="block mt-1 w-full" type="date"
                                    name="tanggal_nota" :value="old('tanggal_nota', $dataExpense->tanggal_nota)" required />
                                <x-input-error :messages="$errors->get('tanggal_nota')" class="mt-2" />
                            </div>

                            <!-- No Nota -->
                            <div>
                                <x-input-label for="no_nota" :value="__('No Nota')" />
                                <x-text-input id="no_nota" class="block mt-1 w-full" type="text" name="no_nota"
                                    :value="old('no_nota', $dataExpense->no_nota)" required />
                                <x-input-error :messages="$errors->get('no_nota')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Kategori -->
                            <div class="select-search">
                                <x-input-label for="kategori" :value="__('Kategori')" />
                                <select name="kategori" id="kategori"
                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                </select>
                                <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <x-input-label for="keterangan" :value="__('Keterangan')" />
                                <x-text-input id="keterangan" class="block mt-1 w-full" type="text" name="keterangan"
                                    :value="old('keterangan', $dataExpense->keterangan)" required />
                                <x-input-error :messages="$errors->get('kode_supplier')" class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            <!-- Harga Satuan -->
                            <div>
                                <x-input-label for="harga_satuan" :value="__('Harga Satuan')" />
                                <x-text-input id="harga_satuan" class="block mt-1 w-full" type="number"
                                    name="harga_satuan" x-model.number="$store.dataExpensesForm.hargaSatuan"
                                    x-on:input="$store.dataExpensesForm.calculateKredit()" :value="old('harga_satuan', $dataExpense->harga_satuan)" required
                                    step="0.01" />
                                <x-input-error :messages="$errors->get('harga_satuan')" class="mt-2" />
                            </div>

                            <!-- Kuantitas -->
                            <div>
                                <x-input-label for="kuantitas" :value="__('Kuantitas')" />
                                <x-text-input id="kuantitas" class="block mt-1 w-full" type="number" name="kuantitas"
                                    x-model="$store.dataExpensesForm.kuantitas"
                                    x-on:input="$store.dataExpensesForm.calculateKredit()" :value="old('kuantitas', $dataExpense->kuantitas)"
                                    required />
                                <x-input-error :messages="$errors->get('kuantitas')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Kredit -->
                        <div>
                            <x-input-label for="kredit" :value="__('Kredit')" />
                            <x-text-input id="kredit" class="block mt-1 w-full" type="number"
                                x-model.number="$store.dataExpensesForm.kredit" placeholder="Terisi Otomatis"
                                name="kredit" readonly required step="0.01" :value="old('kredit', $dataExpense->kredit)" />
                            <x-input-error :messages="$errors->get('kredit')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('data-expenses.index') }}"
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
    const editData = {
        kategori: "{{ $dataExpense->kategori }}",
        harga_satuan: "{{ $dataExpense->harga_satuan }}",
        kuantitas: "{{ $dataExpense->kuantitas }}",
        kredit: "{{ $dataExpense->kredit }}",
    };

    document.addEventListener('alpine:init', () => {
        Alpine.store('dataExpensesForm', {
            hargaSatuan: editData.harga_satuan,
            kuantitas: editData.kuantitas,
            kredit: editData.kredit,

            calculateKredit() {
                const hargaSatuan = parseFloat(this.hargaSatuan) || 0;
                const kuantitas = parseFloat(this.kuantitas) || 0;

                this.kredit = Math.round((hargaSatuan * kuantitas) * 100) / 100;
            },
        });
    })

    function dataExpensesForm() {
        return {
            get hargaSatuan() {
                return Alpine.store('dataExpensesForm').hargaSatuan;
            },
            set hargaSatuan(value) {
                Alpine.store('dataExpensesForm').hargaSatuan = value;
            },
            get kuantitas() {
                return Alpine.store('dataExpensesForm').kuantitas;
            },
            set kuantitas(value) {
                Alpine.store('dataExpensesForm').kuantitas = value;
            },
            get kredit() {
                return Alpine.store('dataExpensesForm').kredit;
            },
            set kredit(value) {
                Alpine.store('dataExpensesForm').kredit = value;
            },
        }
    };

    document.addEventListener('DOMContentLoaded', async () => {
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
        const dropdowns = ['kategori'];
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
            const response = await fetch('/admin/api/data-expenses/data');
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
        const config = {
            id: 'kategori',
            value: editData.kategori,
        };


        const tomSelect = document.getElementById(config.id)?.tomselect;
        if (tomSelect && config.value) {
            // Coba set nilai
            setTimeout(() => {
                if (tomSelect.options[config.value]) {
                    tomSelect.setValue(config.value, true);
                }
            }, 100);
        }
    }

    let lastRequestTime = 0;
    let searchTimeout;

    function initializeTomSelectWithSearch(initialData) {
        const config = {
            id: 'kategori',
            placeholder: '-- Pilih Kategori --'
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
                            `/admin/api/data-expenses/data?q=${encodeURIComponent(query)}`
                        )
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());

                    // console.log(`/admin/api/data-expenses/data?q=${encodeURIComponent(query)}`);
                }, 300);
            },

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
