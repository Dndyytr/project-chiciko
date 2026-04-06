<div class="relative" x-data="{
    open: false,
    tableConfig: {{ json_encode($tableConfig ?? []) }},
    filterField: '{{ $filterField }}',

    shouldShowColumn(config) {
        if (!config.showFor) return true;
        return config.showFor.includes(this[this.filterField]);
    },

    getColumnValue(item, config) {
        if (typeof config.field === 'object') {
            return item[config.field[this[this.filterField]]] || '';
        }
        return item[config.field] || '';
    },

    getColumnCount() {
        return this.tableConfig.filter(col => this.shouldShowColumn(col)).length + 1;
    }
}">
    <div x-on:click="open = ! open">
        {{ $trigger }}
    </div>


    <div x-show="open" x-transition:enter="transition duration-600 ease-out" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition duration-600 ease-in"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-99999999 grid h-screen w-full place-items-center bg-[rgba(0,0,0,0.6)] p-2"
        style="display: none;">

        <div x-show="open" x-transition:enter="transition duration-600 ease-out"
            x-transition:enter-start="-translate-y-[100%] opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-600 ease-in" x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-[100%] opacity-0"
            class="size-full max-w-[80%] max-h-[90%] dark:bg-gray-800 flex flex-col min-h-0 min-w-0 gap-2 rounded-sm md:rounded-md bg-white font-medium shadow-[0_0_4px_0.4px_rgba(0,0,0,0.5)] p-3"
            style="display: none;">

            <div>
                <button type="button" x-on:click="open = false"
                    class="float-right text-white rounded-full p-1 cursor-pointer bg-indigo-600 hover:bg-indigo-900 transition-all duration-300 ease-in-out"><svg
                        class="size-[1.5em]" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 640">
                        <path
                            d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z" />
                    </svg></button>
            </div>

            <div>
                {{ $content }}
            </div>

            {{-- Loading --}}
            <div x-show="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <p class="mt-2 text-gray-600 dark:text-gray-200">Memuat data...</p>
            </div>

            {{-- Table --}}
            <div x-show="!loading" class="overflow-y-auto overflow-x-auto min-h-0 min-w-0 rounded-sm md:rounded-md">
                <table class="w-full text-left text-gray-500 dark:text-gray-200">
                    <thead class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <template x-for="(config, index) in tableConfig.filter(col => shouldShowColumn(col))"
                                :key="index">
                                <th class="px-4 py-3" x-text="config.header"></th>
                            </template>
                            <!-- Gunakan filterField yang dinamis -->
                            <template x-if="$data[filterField]">
                                <th class="px-4 py-3">Aksi</th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Empty State --}}
                        <template x-if="materials.length === 0">
                            <tr>
                                <td :colspan="getColumnCount()"
                                    class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <span
                                        x-text="!$data[filterField] ? 'Silakan pilih ' + filterField + ' terlebih dahulu' : 'Tidak ada data ditemukan'"></span>
                                </td>
                            </tr>
                        </template>

                        {{-- Data Rows --}}
                        <template x-for="item in materials" :key="item.id">
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <template x-for="(config, index) in tableConfig.filter(col => shouldShowColumn(col))"
                                    :key="index">
                                    <td class="px-4 py-3" x-text="getColumnValue(item, config)"></td>
                                </template>
                                <template x-if="$data[filterField]">
                                    <td class="px-4 py-3">
                                        <button type="button" x-on:click="selectMaterial(item); open = false"
                                            class="px-3 py-1 bg-indigo-600 cursor-pointer text-white rounded-sm md:rounded-md hover:bg-indigo-900 transition-all duration-300 ease-in-out">
                                            Pilih
                                        </button>
                                    </td>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
