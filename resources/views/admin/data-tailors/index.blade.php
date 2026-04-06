<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Penjahit') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100"
                    x-data="{ loading: false }" x-on:loading.window="loading = $event.detail">
                    <div class="flex items-center justify-between py-5 mb-5">

                        <div x-data="{ openFilter: false, error: false }" x-on:close-filter.window="openFilter = false"
                            x-on:open-error.window="error = true">
                            <button type="button" x-on:click="openFilter = !openFilter"
                                class="relative cursor-pointer inline-flex items-center px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                <svg class="size-[1.3em]" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                    <path
                                        d="M96 128C83.1 128 71.4 135.8 66.4 147.8C61.4 159.8 64.2 173.5 73.4 182.6L256 365.3L256 480C256 488.5 259.4 496.6 265.4 502.6L329.4 566.6C338.6 575.8 352.3 578.5 364.3 573.5C376.3 568.5 384 556.9 384 544L384 365.3L566.6 182.7C575.8 173.5 578.5 159.8 573.5 147.8C568.5 135.8 556.9 128 544 128L96 128z" />
                                </svg>
                                Filter
                            </button>

                            {{-- isi container filter --}}
                            <div x-show="openFilter" x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition duration-300 ease-in"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-99999999 grid h-screen w-full place-items-center bg-[rgba(0,0,0,0.6)] p-2"
                                style="display: none;">
                                <div x-show="openFilter" x-transition:enter="transition duration-300 ease-out"
                                    x-transition:enter-start="scale-0 opacity-0"
                                    x-transition:enter-end="scale-100 opacity-100"
                                    x-transition:leave="transition duration-300 ease-in"
                                    x-transition:leave-start="scale-100 opacity-100"
                                    x-transition:leave-end="scale-0 opacity-0"
                                    class="size-full max-w-[50%] max-h-[50%] min-h-max dark:bg-gray-800 flex flex-col min-w-0 gap-2 rounded-sm md:rounded-md bg-white font-medium shadow-[0_0_4px_0.4px_rgba(0,0,0,0.5)] p-3"
                                    style="display: none;">

                                    <div>
                                        <button type="button" x-on:click="openFilter = false"
                                            class="float-right text-white rounded-full p-1 cursor-pointer bg-indigo-600 hover:bg-indigo-900 transition-all duration-300 ease-in-out">
                                            <svg class="size-[1.5em]" fill="currentColor"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                                <path
                                                    d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Error State --}}
                                    <div x-show="error"
                                        class="bg-red-200 dark:bg-red-900/20 border border-red-600 dark:border-red-700 rounded-sm md:rounded-md p-2">
                                        <button type="button" x-on:click="error = false"
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
                                            <p class="text-red-800 break-all dark:text-red-300">
                                                Nama Koordinator atau Nama Daerah Harus Diisi!</p>
                                        </div>
                                    </div>

                                    <form id="filterForm" x-data="tailorFilterForm()" @submit.prevent="submitFilter">
                                        <div>
                                            <div class="select-search mb-4">
                                                <x-input-label for="id_nama_koordinator" :value="__('Nama Koordinator')" />
                                                <select name="id_nama_koordinator" id="id_nama_koordinator"
                                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                                </select>
                                                <x-input-error :messages="$errors->get('id_nama_koordinator')" class="mt-2" />
                                                <input type="hidden" name="nama_koordinator" id="nama_koordinator"
                                                    x-model="namaKoordinator">
                                            </div>

                                            <div class="select-search mb-4">
                                                <x-input-label for="id_nama_daerah" :value="__('Nama Daerah')" />
                                                <select name="id_nama_daerah" id="id_nama_daerah"
                                                    class="dark:text-white text-black bg-white w-full focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md mt-1 cursor-pointer">
                                                </select>
                                                <x-input-error :messages="$errors->get('id_nama_daerah')" class="mt-2" />
                                                <input type="hidden" name="nama_daerah" id="nama_daerah"
                                                    x-model="namaDaerah">
                                            </div>
                                        </div>

                                        <div class="flex justify-end mt-4">
                                            <x-primary-button type="submit" class="cursor-pointer">
                                                {{ __('Filter') }}
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Element untuk toolbar --}}
                        <div class="md:mt-0 sm:flex-none w-72">
                            <form action="{{ route('data-tailors.index') }}" method="GET" onsubmit="return false;">
                                <input type="text" name="search" placeholder="Type for search then enter"
                                    class="w-full relative inline-flex items-center px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300" />
                            </form>
                        </div>

                        <div class="sm:ml-16 sm:mt-0 sm:flex-none">
                            <a type="button" href="{{ route('data-tailors.create') }}"
                                class="relative inline-flex items-center px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                Add New
                            </a>
                        </div>
                    </div>

                    {{-- Filter Status Info --}}
                    <div id="filterStatusInfo" class="mb-3" style="display: none;">
                        <div
                            class="bg-indigo-600 text-white px-4 py-3 rounded relative flex items-center justify-between">
                            <span id="filterStatusText"></span>
                            <button type="button" onclick="clearFilter()"
                                class="bg-white hover:bg-indigo-200 text-indigo-600 transition-all duration-300 ease-in-outtext-indigo-600 cursor-pointer px-2 py-1 font-semibold rounded-md">
                                Clear Filter
                            </button>
                        </div>
                    </div>

                    {{-- Loading State --}}
                    <div x-show="loading" class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Memuat data...</p>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-sm text-gray-700 uppercase bg-white dark:bg-gray-800">
                                <tr
                                    class="bg-white border-t border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>No</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Nama Koordinator</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Koordinator</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Nama Daerah</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Nama Penjahit</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Penjahit</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Dibuat Pada</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($dataTailors as $dataTailor)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                                            {{ ++$i }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $dataTailor->nama_koordinator }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $dataTailor->kode_koordinator }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $dataTailor->nama_daerah }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $dataTailor->nama_penjahit }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $dataTailor->kode_penjahit }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_tanggal_id($dataTailor->created_at, true, true) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <form action="{{ route('data-tailors.destroy', $dataTailor->id) }}"
                                                method="POST">
                                                <div class="flex">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="confirmDelete(this.closest('form'))"
                                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                        HAPUS
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center">
                                            <div class="bg-gray-500 text-white p-3 rounded shadow-sm">
                                                Data Belum Tersedia!
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="relative p-3" id="paginationContainer">
                            {{ $dataTailors->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script defer>
    // Global filter state
    let currentFilters = {
        nama_koordinator: '',
        nama_daerah: '',
        search: '',
        page: 1
    };

    function tailorFilterForm() {
        return {
            namaKoordinator: '',
            namaDaerah: '',

            async submitFilter() {
                // pastikan minimal salah satu dipilih
                if (!this.namaKoordinator && !this.namaDaerah) {
                    this.$dispatch('open-error');
                    return;
                }

                // Tutup modal (dispatch event agar parent yang pegang openFilter menutup)
                this.$dispatch('close-filter');

                // Update filters
                currentFilters.nama_koordinator = this.namaKoordinator;
                currentFilters.nama_daerah = this.namaDaerah;
                currentFilters.page = 1;

                // Fetch data
                await fetchData();

                // Update filter info
                updateFilterInfo();

            }
        }
    }

    // Function untuk fetch data dengan AJAX
    async function fetchData() {
        try {
            const params = new URLSearchParams();

            if (currentFilters.nama_koordinator) {
                params.append('nama_koordinator', currentFilters.nama_koordinator);
            }
            if (currentFilters.nama_daerah) {
                params.append('nama_daerah', currentFilters.nama_daerah);
            }
            if (currentFilters.search) {
                params.append('search', currentFilters.search);
            }
            params.append('page', currentFilters.page);

            window.dispatchEvent(new CustomEvent('loading', {
                detail: true
            }));

            const response = await fetch(`{{ route('data-tailors.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network error');

            const data = await response.json();

            // Update table
            document.getElementById('tableBody').innerHTML = data.html;

            // Update pagination
            document.getElementById('paginationContainer').innerHTML = data.pagination;

            // Attach pagination event listeners
            attachPaginationListeners();

        } catch (error) {
            console.error('Error fetching data:', error);
            Swal.fire({
                icon: 'info',
                title: 'Peringatan',
                text: 'Terjadi kesalahan saat memuat data!',

                // styling dengan class tailwind
                customClass: {
                    icon: '!border-[10px] !text-indigo-600 text-xl !mt-5 !mb-0 !size-25 font-bold !border-indigo-600',
                    popup: '!bg-slate-100 dark:!bg-gray-900',
                    title: '!text-stone-900 !font-semibold dark:!text-white',
                    htmlContainer: '!text-stone-800 !font-medium dark:!text-gray-400',
                    confirmButton: '!bg-indigo-600 hover:!bg-indigo-400'
                },
            });
        } finally {
            window.dispatchEvent(new CustomEvent('loading', {
                detail: false
            }));
        }
    }

    // Function untuk update filter info
    function updateFilterInfo() {
        const filterInfo = document.getElementById('filterStatusInfo');
        const filterText = document.getElementById('filterStatusText');

        const filters = [];
        if (currentFilters.nama_koordinator) {
            filters.push(`Koordinator: ${currentFilters.nama_koordinator}`);
        }
        if (currentFilters.nama_daerah) {
            filters.push(`Daerah: ${currentFilters.nama_daerah}`);
        }

        if (filters.length > 0) {
            filterText.textContent = `Filter aktif: ${filters.join(' | ')}`;
            filterInfo.style.display = 'block';
        } else {
            filterInfo.style.display = 'none';
        }
    }

    // Function untuk clear filter
    function clearFilter() {
        // Reset filters
        currentFilters.nama_koordinator = '';
        currentFilters.nama_daerah = '';
        currentFilters.page = 1;

        // Reset TomSelect
        const koordinatorSelect = document.getElementById('id_nama_koordinator');
        const daerahSelect = document.getElementById('id_nama_daerah');

        if (koordinatorSelect?.tomselect) {
            koordinatorSelect.tomselect.clear();
        }
        if (daerahSelect?.tomselect) {
            daerahSelect.tomselect.clear();
        }

        // Fetch data without filter
        fetchData();

        // Hide filter info
        document.getElementById('filterStatusInfo').style.display = 'none';
    }

    // Function untuk attach pagination listeners
    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('#paginationContainer a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                currentFilters.page = url.searchParams.get('page') || 1;
                fetchData();
            });
        });
    }

    document.addEventListener("DOMContentLoaded", async () => {
        if (typeof TomSelect === 'undefined') {
            await waitForTomSelect();
        }

        renderSkeletonDropdowns();
        const initialData = await preloadInitialData();
        initializeTomSelectWithSearch(initialData);

        // Attach pagination listeners on initial load
        attachPaginationListeners();

    });

    function renderSkeletonDropdowns() {
        const dropdowns = ['id_nama_koordinator', 'id_nama_daerah'];
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
            const response = await fetch('/settings/api/tailor-codes/data');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            window.dropdownData = data;
            return data;
        } catch (error) {
            console.error('Error preloading initial data:', error);
            const emptyData = {
                coordinatorCodes: [],
                areaCodes: [],
            };
            window.dropdownData = emptyData;
            return emptyData;
        }
    }

    let lastRequestTime = 0;
    let searchTimeout;

    function initializeTomSelectWithSearch(initialData) {
        const configs = [{
                id: 'id_nama_koordinator',
                type: 'coordinatorCodes',
                placeholder: '-- Pilih Nama Koordinator --'
            },
            {
                id: 'id_nama_daerah',
                type: 'areaCodes',
                placeholder: '-- Pilih Nama Daerah --'
            },
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

                onInitialize: function() {
                    if (initialData && initialData[config.type]) {
                        initialData[config.type].forEach(item => this.addOption(item));
                        this.refreshOptions(false);
                    }
                },

                load: function(query, callback) {
                    if (query.length < 2) return callback();

                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const now = Date.now();
                        if (now - lastRequestTime < 1000) {
                            console.log('Too frequent, using cached data');
                            return callback([]);
                        }

                        fetch(
                                `/settings/api/tailor-codes/data?q=${encodeURIComponent(query)}`
                            )
                            .then(response => {
                                lastRequestTime = Date.now();
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
                    const formElement = document.getElementById('filterForm');
                    if (!formElement) return;

                    const alpineComponent = Alpine.$data(formElement);
                    if (!alpineComponent) return;

                    switch (config.id) {
                        case 'id_nama_koordinator':
                            const coordinatorCode = window.dropdownData?.coordinatorCodes.find(cc =>
                                cc.value === value);
                            alpineComponent.namaKoordinator = coordinatorCode?.nama_koordinator ||
                                '';
                            break;
                        case 'id_nama_daerah':
                            const areaCode = window.dropdownData?.areaCodes.find(ac => ac.value ===
                                value);
                            alpineComponent.namaDaerah = areaCode?.nama_daerah || '';
                            break;
                    }
                }
            });

            addCloseAnimation(tomSelect);
        });
    }

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
