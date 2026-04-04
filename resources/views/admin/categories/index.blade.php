<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Kategori
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add New -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="flex items-center space-x-4">
                    @csrf
                    <div class="flex-1">
                        <x-input-label for="name" value="Nama Kategori" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div class="pt-6">
                        <x-primary-button>Tambah</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                        <tr x-data="{ editing: false }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-show="!editing" class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                <form x-show="editing" id="form-{{ $category->id }}" action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf @method('PUT')
                                    <x-text-input name="name" value="{{ $category->name }}" class="w-full text-sm py-1" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-show="!editing">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                                <div x-show="editing">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ $category->is_active ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Aktif</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div x-show="!editing" class="flex justify-end space-x-3">
                                    <button @click="editing = true" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                                <div x-show="editing" class="flex justify-end space-x-2">
                                    <button type="submit" form="form-{{ $category->id }}" class="text-green-600 hover:text-green-900">Simpan</button>
                                    <button type="button" @click="editing = false" class="text-gray-600 hover:text-gray-900">Batal</button>
                                </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
