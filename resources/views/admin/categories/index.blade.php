<x-app-layout>
    <x-slot name="header">
        {{ __('Manajemen Kategori') }}
    </x-slot>

    <div class="row g-4">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Add New -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-12 col-md">
                            <x-input-label for="name" value="Tambah Kategori Baru" />
                            <x-text-input id="name" name="name" type="text" placeholder="Masukkan nama kategori..." required />
                        </div>
                        <div class="col-12 col-md-auto">
                            <x-primary-button class="w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg me-1" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                                </svg>
                                Tambah
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List -->
            <div class="card border-0 shadow-sm mt-0">
                <div class="card-header bg-white border-bottom-0 py-2">
                    <h5 class="card-title fw-bold mb-0 text-dark">Daftar Kategori</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0">Nama Kategori</th>
                                    <th class="px-4 py-3 border-0">Status</th>
                                    <th class="px-4 py-3 border-0 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td class="px-4 py-3 fw-medium text-dark">{{ $category->name }}</td>
                                    <td class="px-4 py-3">
                                        @if($category->is_active)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3">Aktif</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-3">Hapus</button>
                                            </form>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade text-start" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content border-0 shadow">
                                                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <div class="modal-header border-bottom-0">
                                                            <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body py-4">
                                                            <div class="mb-4">
                                                                <x-input-label for="name{{ $category->id }}" value="Nama Kategori" />
                                                                <x-text-input id="name{{ $category->id }}" name="name" type="text" value="{{ $category->name }}" required />
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="is_active" id="active{{ $category->id }}" {{ $category->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-medium" for="active{{ $category->id }}">Kategori Aktif</label>
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
