<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Database Bahan') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between py-5 mb-5">

                        <a href="/export/database_materials"
                            class="relative inline-flex items-center px-4 py-2 font-medium
text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500
focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100
active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600
dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300">Export</a>

                        {{-- Element untuk toolbar --}}
                        <div class="md:mt-0 sm:flex-none w-72">
                            <form action="{{ route('database-materials.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Type for search then enter"
                                    class="w-full relative inline-flex items-center px-4 py-2 font-medium
text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500
focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100
active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600
dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300" />
                            </form>

                        </div>
                        <div class="sm:ml-16 sm:mt-0 sm:flex-none">
                            <a type="button" href="{{ route('database-materials.create') }}"
                                class="relative inline-flex items-center px-4 py-2 font-medium
text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500
focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100
active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600
dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                Add New
                            </a>
                        </div>
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        {{-- Element untuk tabel --}}

                        <table class="w-full text-sm text-left rtl:text-right text-gray-500
dark:text-gray-400">
                            <thead class="text-sm text-gray-700 uppercase bg-white dark:bg-gray-800 ">
                                <tr
                                    class="bg-white border-t border-b dark:bg-gray-800
dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>No</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Name</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Status</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Bahan</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Text JP</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode JP</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Text Lvl1 JB</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Lvl1 JB</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Text Lvl2 JB</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Lvl2 JB</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Text Lvl3 JB</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Lvl3 JB</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Text Warna</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Warna</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($databaseMaterials as $databaseMaterial)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700
hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white
text-center">
                                            {{ ++$i }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->name }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->status }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->kode_bahan }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->text_jp }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->kode_jp }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->text_lvl1_jb }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->kode_lvl1_jb }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->text_lvl2_jb }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->kode_lvl2_jb }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->text_lvl3_jb }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->kode_lvl3_jb }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->text_warna }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $databaseMaterial->kode_warna }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <form
                                                action="{{ route('database-materials.destroy', $databaseMaterial->id) }}"
                                                method="POST">
                                                <div class="flex">
                                                    <a href="{{ route('database-materials.edit', $databaseMaterial->id) }}"
                                                        class="focus:outline-none text-gray-50 bg-yellow-400
hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-5 py-2.5 me-2
mb-2 dark:focus:ring-yellow-900">EDIT</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this.closest('form'))"
                                                        class="focus:outline-none text-white bg-red-700
hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-5 py-2.5 me-2 mb-2
dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                        HAPUS</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="bg-gray-500 text-white p-3 rounded shadow-sm mb-3">
                                        Data Belum Tersedia!
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="relative p-3">
                            {{ $databaseMaterials->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
