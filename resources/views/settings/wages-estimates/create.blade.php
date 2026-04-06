<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Perkiraan Upah') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('wages-estimates.store') }}">
                        @csrf
                        <!-- Nama -->
                        <div>
                            <x-input-label for="nama" :value="__('Nama')" />
                            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama"
                                :value="old('nama')" autofocus required />
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <!-- Kode -->
                        <div>
                            <x-input-label for="kode" :value="__('Kode')" />
                            <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode"
                                :value="old('kode')" autofocus required />
                            <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                        </div>

                        {{-- Helper --}}
                        <div>
                            <x-input-label for="helper" :value="__('Helper')" />
                            <x-text-input id="helper" class="block mt-1 w-full" type="text" name="helper"
                                :value="old('helper')" required />
                            <x-input-error :messages="$errors->get('helper')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('wages-estimates.index') }}"
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
