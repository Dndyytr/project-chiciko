<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Data Gudang') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('data-warehouses.store') }}">
                        @csrf
                        <!-- Nama Gudang -->
                        <div>
                            <x-input-label for="nama_gudang" :value="__('Nama Gudang')" />
                            <x-text-input id="nama_gudang" class="block mt-1 w-full" type="text" name="nama_gudang"
                                :value="old('nama_gudang')" autofocus required />
                            <x-input-error :messages="$errors->get('nama_gudang')" class="mt-2" />
                        </div>
                        <!-- Lokasi -->
                        <div>
                            <x-input-label for="lokasi" :value="__('Lokasi')" />
                            <textarea name="lokasi" id="lokasi" rows="3"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 ring-transparent px-3 py-[6px] dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 outline-none border-[1px] ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required></textarea>
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('data-warehouses.index') }}"
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
