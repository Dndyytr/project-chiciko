<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Barang Masuk Baku') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mx-auto grid w-full py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between py-5 mb-5">

                        <a href="/export/incoming_raw_materials"
                            class="relative inline-flex items-center px-4 py-2 font-medium
text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500
focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100
active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600
dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300">Export</a>

                        {{-- Element untuk toolbar --}}
                        <div class="md:mt-0 sm:flex-none w-72">
                            <form action="{{ route('incoming-raw-materials.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Type for search then enter"
                                    class="w-full relative inline-flex items-center px-4 py-2 font-medium
text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500
focus:outline-none focus:ring ring-gray-300 focus:border-indigo-300 active:bg-gray-100
active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600
dark:text-gray-300 dark:focus:border-indigo-700 dark:active:bg-gray-700 dark:active:text-gray-300" />
                            </form>

                        </div>
                        <div class="sm:ml-16 sm:mt-0 sm:flex-none">
                            <a type="button" href="{{ route('incoming-raw-materials.create') }}"
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
                                        <span>Tanggal Nota</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>No Kwitansi</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Supplier</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Nama Supplier</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kode Barcode</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Satuan Ukur</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Nama Barang</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Jenis Kain</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Warna</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Yard</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Nama Barang Detail</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>QTY Roll</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Kg Per Roll</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Jumlah Roll X Satuan</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Harga Per Satuan</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Harga Awal</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Nominal Diskon</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Total Diskon</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Total Harga</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <span>Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incomingRawMaterials as $incomingRawMaterial)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700
hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white
text-center">
                                            {{ ++$i }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_tanggal_id($incomingRawMaterial->tanggal_nota, false, true) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->no_kwitansi }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->kode_supplier }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->nama_supplier }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->kode_barcode }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->satuan_ukur }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->nama_barang }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->jenis_kain }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->warna }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_number_id($incomingRawMaterial->yard) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ $incomingRawMaterial->nama_barang_detail }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_number_id($incomingRawMaterial->qty_roll) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_number_id($incomingRawMaterial->kg_roll) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_number_id($incomingRawMaterial->jumlah_roll_satuan) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_rupiah_id($incomingRawMaterial->harga_per_satuan) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_rupiah_id($incomingRawMaterial->harga_awal) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_rupiah_id($incomingRawMaterial->nominal_diskon) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_rupiah_id($incomingRawMaterial->total_diskon) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            {{ format_rupiah_id($incomingRawMaterial->total_harga) }}
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <form
                                                action="{{ route('incoming-raw-materials.destroy', $incomingRawMaterial->id) }}"
                                                method="POST">
                                                <div class="flex">
                                                    <a href="{{ route('incoming-raw-materials.edit', $incomingRawMaterial->id) }}"
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
                            {{ $incomingRawMaterials->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
