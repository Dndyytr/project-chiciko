<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Satuan Supplier') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('unit-suppliers.update', $unitSupplier->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Nilai -->
                        <div>
                            <x-input-label for="nilai" :value="__('Nilai')" />
                            <x-text-input id="nilai" class="block mt-1 w-full" type="number" name="nilai"
                                :value="old('nilai', $unitSupplier->nilai)" autofocus required />
                            <x-input-error :messages="$errors->get('nilai')" class="mt-2" />
                        </div>

                        {{-- Satuan Ukur --}}
                        <div>
                            <x-input-label for="satuan_ukur" :value="__('Satuan Ukur')" />
                            <x-text-input id="satuan_ukur" class="block mt-1 w-full" type="text" name="satuan_ukur"
                                :value="old('satuan_ukur', $unitSupplier->satuan_ukur)" required />
                            <x-input-error :messages="$errors->get('satuan_ukur')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('unit-suppliers.index') }}"
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
