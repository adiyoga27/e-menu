<x-app-layout>
    <x-slot name="header">
        {{ __('Manajemen Menu') }}
    </x-slot>

    <div class="row g-4">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Add New -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-2 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-dark">Daftar Menu</h5>
                    <button class="btn btn-primary btn-sm px-4 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#addMenuForm" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                        </svg>
                        Tambah Menu Baru
                    </button>
                </div>
                <div class="collapse {{ $errors->any() ? 'show' : '' }}" id="addMenuForm">
                    <div class="card-body p-4 border-top">
                        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-input-label for="category_id" value="Kategori" />
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="name" value="Nama Menu" />
                                    <x-text-input id="name" name="name" type="text" placeholder="Masukkan nama menu..." required />
                                </div>
                                <div class="col-md-12">
                                    <x-input-label for="description" value="Deskripsi" />
                                    <x-text-input id="description" name="description" type="text" placeholder="Contoh: Manis, segar, dingin..." />
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="price" value="Harga (Rp)" />
                                    <x-text-input id="price" name="price" type="number" placeholder="Contoh: 15000" required />
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="image" value="Gambar Menu" />
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch py-2">
                                        <input class="form-check-input" type="checkbox" name="is_ready" id="is_ready" checked>
                                        <label class="form-check-label fw-medium" for="is_ready">Menu Tersedia (Ready)</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <x-primary-button>Simpan Menu</x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- List -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0">Info Menu</th>
                                    <th class="px-4 py-3 border-0 text-center">Kategori</th>
                                    <th class="px-4 py-3 border-0 text-center">Harga</th>
                                    <th class="px-4 py-3 border-0 text-center">Status</th>
                                    <th class="px-4 py-3 border-0 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus as $menu)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            @if($menu->image)
                                                <img src="{{ asset('storage/' . $menu->image) }}" class="rounded shadow-sm me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3 text-muted" style="width: 48px; height: 48px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark">{{ $menu->name }}</div>
                                                <div class="small text-muted">{{ Str::limit($menu->description, 35) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3">{{ $menu->category->name }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center fw-bold text-dark">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($menu->is_ready)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3">Ready</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3">Kosong</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary px-3" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-3">Hapus</button>
                                            </form>
                                        </div>

                                        <!-- Edit Menu Modal -->
                                        <div class="modal fade text-start" id="editMenuModal{{ $menu->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content border-0 shadow">
                                                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf @method('PUT')
                                                        <div class="modal-header border-bottom-0">
                                                            <h5 class="modal-title fw-bold">Edit Menu</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body py-4">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <x-input-label value="Kategori" />
                                                                    <select name="category_id" class="form-select">
                                                                        @foreach($categories as $category)
                                                                            <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <x-input-label value="Nama Menu" />
                                                                    <x-text-input name="name" value="{{ $menu->name }}" required />
                                                                </div>
                                                                <div class="col-12">
                                                                    <x-input-label value="Deskripsi" />
                                                                    <x-text-input name="description" value="{{ $menu->description }}" />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <x-input-label value="Harga (Rp)" />
                                                                    <x-text-input name="price" type="number" value="{{ $menu->price }}" required />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <x-input-label value="Gambar Baru (Opsional)" />
                                                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-check form-switch py-2">
                                                                        <input class="form-check-input" type="checkbox" name="is_ready" id="is_ready{{ $menu->id }}" {{ $menu->is_ready ? 'checked' : '' }}>
                                                                        <label class="form-check-label fw-medium" for="is_ready{{ $menu->id }}">Menu Tersedia (Ready)</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0">
                                                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                                            <x-primary-button>Simpan Perubahan</x-primary-button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
