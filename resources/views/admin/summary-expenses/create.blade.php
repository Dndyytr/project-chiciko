<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Rekapan Pengeluaran') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('summary-expenses.store') }}" x-data="summaryExpenseForm()"
                        class="w-full">
                        @csrf

                        <div class="w-full grid grid-cols-2 gap-2 lg:gap-4">
                            {{-- Tanggal Mulai --}}
                            <div>
                                <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                                <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date"
                                    name="tanggal_mulai" :value="old('tanggal_mulai')"
                                    x-model="$store.summaryExpenseForm.tanggalMulai"
                                    x-on:change="$store.summaryExpenseForm.reloadData()"
                                    x-bind:required="!$store.summaryExpenseForm.tanggalAkhir" />
                                <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                            </div>

                            {{-- Tanggal Akhir --}}
                            <div>
                                <x-input-label for="tanggal_akhir" :value="__('Tanggal Akhir')" />
                                <x-text-input id="tanggal_akhir" class="block mt-1 w-full" type="date"
                                    name="tanggal_akhir" :value="old('tanggal_akhir')"
                                    x-model="$store.summaryExpenseForm.tanggalAkhir"
                                    x-on:change="$store.summaryExpenseForm.reloadData()"
                                    x-bind:required="!$store.summaryExpenseForm.tanggalMulai" />
                                <x-input-error :messages="$errors->get('tanggal_akhir')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Cari Kategori --}}
                        <div class="w-full mt-2 relative shadow-md p-3">
                            <div class="grid grid-cols-2 gap-2 lg:gap-4" x-data="{ openCategory: false }">
                                <label class="flex items-center gap-1"><x-heroicon-s-magnifying-glass
                                        class="size-[1.3em]"></x-heroicon-s-magnifying-glass> Cari
                                    Kategori</label>
                                <button type="button" x-on:click="openCategory = !openCategory"
                                    class="bg-indigo-600 px-4 py-2 z-999 size-max place-self-end rounded-md uppercase cursor-pointer hover:bg-indigo-900">+
                                    Tambah</button>

                                <div class="max-h-[140%] right-33 top-0 z-99 shadow-md dark:bg-gray-800 bg-white p-2 absolute overflow-y-auto overflow-x-auto min-h-0 min-w-0 rounded-sm md:rounded-md"
                                    x-transition:enter="transition duration-400 ease-out"
                                    x-transition:enter-start="translate-x-[20%] opacity-0"
                                    x-transition:enter-end="translate-x-0 opacity-100"
                                    x-transition:leave="transition duration-400 ease-in"
                                    x-transition:leave-start="translate-x-0 opacity-100"
                                    x-transition:leave-end="translate-x-[20%] opacity-0" x-show="openCategory"
                                    style="display: none;">
                                    <div class="inline-flex flex-col gap-2">
                                        <div>
                                            <button type="button" x-on:click="openCategory = false"
                                                class="float-right m-1 text-white rounded-full p-1 cursor-pointer bg-indigo-600 hover:bg-indigo-900 transition-all duration-300 ease-in-out"><svg
                                                    class="size-[1.2em]" fill="currentColor"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                                    <path
                                                        d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z" />
                                                </svg></button>
                                            <div>
                                                <x-input-label for="cari_kategori" :value="__('Cari Kategori')" />
                                                <x-text-input id="cari_kategori" class="block mt-1 w-full"
                                                    type="search" placeholder="Cari Kategori"
                                                    x-model="$store.summaryExpenseForm.searchKategori"
                                                    x-on:input.debounce.500ms="$store.summaryExpenseForm.searchData()" />
                                                <x-input-error :messages="$errors->get('cari_kategori')" class="mt-2" />
                                            </div>
                                        </div>

                                        {{-- Error State --}}
                                        <div x-show="$store.summaryExpenseForm.error"
                                            class="bg-red-200 dark:bg-red-900/20 border border-red-600 dark:border-red-700 rounded-sm md:rounded-md p-2">
                                            <div>
                                                <button type="button"
                                                    x-on:click="$store.summaryExpenseForm.error = false"
                                                    class="ml-2 cursor-pointer float-right text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg class="size-[1.2em]" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <div class="inline-flex gap-1 items-start">
                                                    <x-heroicon-s-exclamation-triangle
                                                        class="size-[1.3em] text-red-600 dark:text-red-700"></x-heroicon-s-exclamation-triangle>
                                                    <div class="break-all">
                                                        <h3 class="font-medium text-red-800 dark:text-red-300">
                                                            Terjadi Kesalahan</h3>
                                                        <p class="mt-1 text-red-700 dark:text-red-400"
                                                            x-text="$store.summaryExpenseForm.errorMessage"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Loading State --}}
                                        <div x-show="$store.summaryExpenseForm.loading" class="text-center py-4">
                                            <div
                                                class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600">
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500">Memuat data...</p>
                                        </div>

                                        {{-- Data List --}}
                                        <ul class="flex-col inline-flex gap-2"
                                            x-show="!$store.summaryExpenseForm.loading">
                                            <template x-for="item in $store.summaryExpenseForm.filteredData"
                                                :key="item.id">
                                                <li
                                                    class="w-full border-b dark:border-gray-700 p-2 inline-flex justify-between items-center gap-2">
                                                    <div class="flex-1">
                                                        <div class="font-medium" x-text="item.kategori"></div>
                                                        <div class="text-sm text-gray-500">
                                                            <span x-text="item.tanggal_nota"></span> -
                                                            <span x-text="'Rp ' + formatRupiah(item.kredit)"></span>
                                                        </div>
                                                    </div>
                                                    <button type="button"
                                                        x-on:click="$store.summaryExpenseForm.pilihKategori(item)"
                                                        class="bg-indigo-600 hover:bg-indigo-900 rounded-md px-4 py-2 cursor-pointer text-white whitespace-nowrap">
                                                        Pilih
                                                    </button>
                                                </li>
                                            </template>

                                            {{-- Jika tidak ada data --}}
                                            <template
                                                x-if="$store.summaryExpenseForm.filteredData.length === 0 && !$store.summaryExpenseForm.loading">
                                                <li class="text-center text-gray-900 dark:text-gray-500 py-4">
                                                    <h1 class="font-semibold">Tidak ada data ditemukan</h1>
                                                    <p class="text-sm mt-1">Coba ubah filter tanggal atau kata kunci
                                                        pencarian</p>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Tabel Kategori Terpilih --}}
                            <div
                                class="w-full rounded-sm md:rounded-md mt-2 overflow-x-auto overflow-y-auto min-h-0 min-w-0">
                                <table class="w-full">
                                    <thead
                                        class="text-gray-700 uppercase text-left bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-4 py-2">Kategori</th>
                                            <th class="px-4 py-2">Tanggal</th>
                                            <th class="px-4 py-2">Total Uang Keluar</th>
                                            <th class="px-4 py-2 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, index) in $store.summaryExpenseForm.selectedKategori"
                                            :key="index">
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-4 py-2">
                                                    <span x-text="item.kategori"></span>
                                                    <input type="hidden" :name="'kategori[' + index + ']'"
                                                        :value="item.kategori">
                                                    <input type="hidden" :name="'data_expenses_id[' + index + ']'"
                                                        :value="item.id">
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span x-text="item.tanggal_nota"></span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span x-text="'Rp ' + formatRupiah(item.kredit)"></span>
                                                    <input type="hidden" :name="'total_uang_keluar[' + index + ']'"
                                                        :value="item.kredit">
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    <button type="button"
                                                        x-on:click="$store.summaryExpenseForm.hapusKategori(index)"
                                                        class="bg-red-600 hover:bg-red-900 rounded-md px-4 py-2 cursor-pointer text-white inline-flex items-center gap-1 mx-auto">
                                                        <x-heroicon-s-trash class="size-[1.3em]"></x-heroicon-s-trash>
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>

                                        {{-- Jika tidak ada data --}}
                                        <template x-if="$store.summaryExpenseForm.selectedKategori.length === 0">
                                            <tr class="bg-white dark:bg-gray-800">
                                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                    Belum ada kategori dipilih
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Total Keseluruhan --}}
                        <div class="mt-2">
                            <x-input-label for="total_keseluruhan" :value="__('Total Keseluruhan')" />
                            <x-text-input id="total_keseluruhan" name="total_keseluruhan" type="number"
                                class="block mt-1 w-full" readonly required step="0.01"
                                placeholder="Terisi Otomatis" x-model="$store.summaryExpenseForm.totalKeseluruhan" />
                            <x-input-error :messages="$errors->get('total_keseluruhan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('summary-expenses.index') }}"
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
    document.addEventListener('alpine:init', () => {
        Alpine.store('summaryExpenseForm', {
            // inisialisasi state
            tanggalMulai: '',
            tanggalAkhir: '',
            searchKategori: '',
            loading: false,
            error: false,
            errorMessage: '',
            allData: [],
            filteredData: [],
            selectedKategori: [],
            totalKeseluruhan: '',


            // load inisial data
            async init() {
                await this.loadInitialData();
            },

            // fungsi load inisial data
            async loadInitialData() {
                this.loading = true;
                this.error = false;

                try {
                    // jika tanggal mulai atau tanggal akhir tidak diisi
                    if (!this.tanggalMulai && !this.tanggalAkhir) {
                        this.error = true;
                        this.errorMessage = 'Tanggal mulai atau tanggal akhir harus diisi';
                        return;
                    }

                    // ambil data API
                    const response = await fetch('/admin/api/summary-expenses/data');

                    // jika status pengambilan tidak ok
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.error ||
                            `HTTP ${response.status}: ${response.statusText}`);
                    }

                    // masukkan hasil pengambilan ke variabel data
                    const data = await response.json();
                    this.allData = data;
                    this.filteredData = data;
                    window.summaryExpenseData = data; // Simpan ke global
                } catch (error) {
                    console.error('Load data error:', error);
                    this.error = true;
                    this.errorMessage = error.message || 'Gagal memuat data. Silakan coba lagi.';
                    this.allData = [];
                    this.filteredData = [];
                } finally {
                    this.loading = false;
                }
            },

            async reloadData() {
                // Validasi tanggal di frontend, jika tanggal mulai lebih dari tanggal akhir
                if (new Date(this.tanggalMulai) > new Date(this.tanggalAkhir)) {
                    this.error = true;
                    this.errorMessage = 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir';
                    this.filteredData = [];
                    return;
                }

                this.loading = true;
                this.error = false;
                try {

                    // jika ada tanggal mulai dan tanggal akhir, ambil data dari API dengan parameter
                    const params = new URLSearchParams({
                        tanggal_mulai: this.tanggalMulai,
                        tanggal_akhir: this.tanggalAkhir
                    });

                    const response = await fetch(`/admin/api/summary-expenses/data?${params}`);
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.error ||
                            `HTTP ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();
                    this.allData = data;
                    this.filteredData = data;
                    this.searchKategori = '';
                } catch (error) {
                    console.error('Reload data error:', error);
                    this.error = true;
                    this.errorMessage = error.message || 'Gagal memuat data. Silakan coba lagi.';
                    this.filteredData = [];
                } finally {
                    this.loading = false;
                }
            },

            async searchData() {
                // pencarian tidak boleh kurang dari 2 karakter
                if (this.searchKategori.length < 2) {
                    this.filteredData = this.allData;
                    return;
                }

                this.loading = true;
                try {
                    // isi pencarian dimasukkan kedalam parameter q
                    const params = new URLSearchParams({
                        q: this.searchKategori
                    });

                    // jika tanggal mulai atau tanggal akhir tidak diisi
                    if (!this.tanggalMulai && !this.tanggalAkhir) {
                        this.error = true;
                        this.errorMessage = 'Tanggal mulai atau tanggal akhir harus diisi';
                        return;
                    }

                    // jika ada tanggal mulai dan tanggal akhir maka ikut ke parameter
                    if (this.tanggalMulai) params.append('tanggal_mulai', this.tanggalMulai);
                    if (this.tanggalAkhir) params.append('tanggal_akhir', this.tanggalAkhir);

                    const response = await fetch(`/admin/api/summary-expenses/data?${params}`);
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.error ||
                            `HTTP ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();
                    this.filteredData = data;
                } catch (error) {
                    console.error('Search error:', error);
                    this.error = true;
                    this.errorMessage = error.message || 'Gagal memuat data. Silakan coba lagi.';
                    this.filteredData = [];
                } finally {
                    this.loading = false;
                }
            },

            pilihKategori(item) {
                // Cek apakah kategori dengan id yang sama sudah dipilih
                const exists = this.selectedKategori.find(k => k.id === item.id);

                // jika ya tampilkan info
                if (exists) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Peringatan',
                        text: 'Data ini sudah dipilih!',

                        // styling dengan class tailwind
                        customClass: {
                            icon: '!border-[10px] !text-indigo-600 text-xl !mt-5 !mb-0 !size-25 font-bold !border-indigo-600',
                            popup: '!bg-slate-100 dark:!bg-gray-900',
                            title: '!text-stone-900 !font-semibold dark:!text-white',
                            htmlContainer: '!text-stone-800 !font-medium dark:!text-gray-400',
                            confirmButton: '!bg-indigo-600 hover:!bg-indigo-400'
                        },
                    });
                    return;
                }

                // tambah data kategori kedalam tabel
                this.selectedKategori.push({
                    id: item.id,
                    kategori: item.kategori,
                    tanggal_nota: item.tanggal_nota,
                    kredit: item.kredit
                });

                this.hitungTotal();
            },

            // hapus kategori
            hapusKategori(index) {
                this.selectedKategori.splice(index, 1);
                this.hitungTotal();
            },

            // total keseluruhan
            hitungTotal() {
                const totalKeseluruhan = this.selectedKategori.reduce((sum, item) => {
                    return sum + parseFloat(item.kredit || 0);
                }, 0);

                this.totalKeseluruhan = Math.round(totalKeseluruhan * 100) / 100;
            },

        });
    });

    // format rupiah
    function summaryExpenseForm() {
        return {
            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        }
    }
</script>
