<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah DP Supplier') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('list-supplier-estimates.store') }}">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Inisial -->
                            <div>
                                <x-input-label for="inisial" :value="__('Inisial')" />
                                <x-text-input id="inisial" class="block mt-1 w-full" type="text" name="inisial"
                                    :value="old('inisial')" autofocus required />
                                <x-input-error :messages="$errors->get('inisial')" class="mt-2" />
                            </div>
                            {{-- Nama Supplier --}}
                            <div>
                                <x-input-label for="nama_supplier" :value="__('Nama Supplier')" />
                                <x-text-input id="nama_supplier" class="block mt-1 w-full" type="text"
                                    name="nama_supplier" :value="old('nama_supplier')" required />
                                <x-input-error :messages="$errors->get('nama_supplier')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            {{-- Kontak --}}
                            <div>
                                <x-input-label for="kontak" :value="__('Kontak')" />
                                <x-text-input id="kontak" class="block mt-1 w-full" type="text" name="kontak"
                                    :value="old('kontak')" required />
                                <x-input-error :messages="$errors->get('kontak')" class="mt-2" />
                            </div>
                            {{-- Rekening --}}
                            <div>
                                <x-input-label for="rekening" :value="__('Rekening')" />
                                <x-text-input id="rekening" class="block mt-1 w-full" type="text" name="rekening"
                                    :value="old('rekening')" required />
                                <x-input-error :messages="$errors->get('rekening')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Kode --}}
                        <div>
                            <x-input-label for="kode" :value="__('Kode')" />
                            <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode"
                                :value="old('kode')" required />
                            <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                        </div>


                        {{-- Alamat --}}
                        <div>
                            <x-input-label for="alamat" :value="__('Alamat')" />
                            <textarea name="alamat" id="alamat"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="5" cols="50" required>{{ old('alamat') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('list-supplier-estimates.index') }}"
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
