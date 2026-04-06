<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Raw Material') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('raw-materials.update', $rawMaterial->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Kode Bahan Baku -->
                        <div>
                            <x-input-label for="kode_bahan_baku" :value="__('Kode Bahan Baku')" />
                            <x-text-input id="kode_bahan_baku" class="block mt-1 w-full" type="text"
                                name="kode_bahan_baku" :value="old('kode_bahan_baku', $rawMaterial->kode_bahan_baku)" required autofocus />
                            <x-input-error :messages="$errors->get('kode_bahan_baku')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label :value="__('Status')" />
                            <div class="flex items-center space-x-4 mt-2">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="Aktif"
                                        {{ $rawMaterial->status == 'Aktif' ? 'checked' : '' }}
                                        class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="ml-2">Aktif</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="Non Aktif"
                                        {{ $rawMaterial->status == 'Non Aktif' ? 'checked' : '' }}
                                        class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="ml-2">Non Aktif</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('raw-materials.index') }}"
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
