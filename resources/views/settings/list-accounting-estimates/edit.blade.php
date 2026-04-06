<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah DP Akuntansi') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST"
                        action="{{ route('list-accounting-estimates.update', $listAccountingEstimate->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Nama -->
                        <div>
                            <x-input-label for="nama" :value="__('Nama')" />
                            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama"
                                :value="old('nama', $listAccountingEstimate->nama)" autofocus required />
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        {{-- Kode --}}
                        <div>
                            <x-input-label for="kode" :value="__('Kode')" />
                            <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode"
                                :value="old('kode', $listAccountingEstimate->kode)" required />
                            <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                        </div>

                        {{-- Jenis --}}
                        <div>
                            <x-input-label for="jenis" :value="__('Jenis')" />
                            <x-text-input id="jenis" class="block mt-1 w-full" type="text" name="jenis"
                                :value="old('jenis', $listAccountingEstimate->jenis)" required />
                            <x-input-error :messages="$errors->get('jenis')" class="mt-2" />
                        </div>

                        {{-- Penjelasan --}}
                        <div>
                            <x-input-label for="penjelasan" :value="__('Penjelasan')" />
                            <textarea name="penjelasan" id="penjelasan"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="5" cols="50" required>{{ old('penjelasan', $listAccountingEstimate->penjelasan) }}</textarea>
                            <x-input-error :messages="$errors->get('penjelasan')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('list-accounting-estimates.index') }}"
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
