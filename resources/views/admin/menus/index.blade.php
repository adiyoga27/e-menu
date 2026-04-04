<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Menu
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add New -->
            <div x-data="{ open: false }" class="bg-white shadow-sm sm:rounded-lg p-6">
                <button @click="open = !open" class="flex justify-between w-full items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Menu Baru</h3>
                    <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="mt-4 border-t pt-4">
                    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <x-input-label for="category_id" value="Kategori" />
                            <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500:border-indigo-600 focus:ring-indigo-500:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="name" value="Nama Menu" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="price" value="Harga (Rp)" />
                            <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="image" value="Gambar" />
                            <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                        </div>
                        <div class="flex items-center mt-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_ready" class="rounded border-gray-300 text-indigo-600 shadow-sm" checked>
                                <span class="ml-2 text-sm text-gray-600">Ready</span>
                            </label>
                        </div>
                        <div class="md:col-span-2 pt-4">
                            <x-primary-button>Simpan Menu</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info Menu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($menus as $menu)
                        <tr x-data="{ editing: false }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-show="!editing" class="flex items-center">
                                    @if($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" class="w-10 h-10 rounded object-cover mr-3">
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $menu->name }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($menu->description, 30) }}</div>
                                    </div>
                                </div>
                                <form x-show="editing" id="form-{{ $menu->id }}" action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                    @csrf @method('PUT')
                                    <x-text-input name="name" value="{{ $menu->name }}" class="w-full text-sm py-1" />
                                    <x-text-input name="description" value="{{ $menu->description }}" class="w-full text-sm py-1" placeholder="Deskripsi" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-show="!editing" class="text-sm text-gray-500">{{ $menu->category->name }}</div>
                                <div x-show="editing">
                                    <select name="category_id" class="w-full text-sm py-1 border-gray-300 rounded-md">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-show="!editing" class="text-sm text-gray-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                                <div x-show="editing">
                                    <x-text-input name="price" value="{{ $menu->price }}" type="number" class="w-32 text-sm py-1" />
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-show="!editing">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $menu->is_ready ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $menu->is_ready ? 'Ready' : 'Kosong' }}
                                    </span>
                                </div>
                                <div x-show="editing">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_ready" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ $menu->is_ready ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Ready</span>
                                    </label>
                                    <input type="file" name="image" class="mt-2 block w-full text-xs text-gray-500" accept="image/*">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium align-top">
                                <div x-show="!editing" class="flex justify-end space-x-3">
                                    <button @click="editing = true" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                                <div x-show="editing" class="flex flex-col items-end space-y-2">
                                    <button type="submit" form="form-{{ $menu->id }}" class="text-green-600 hover:text-green-900">Simpan</button>
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
