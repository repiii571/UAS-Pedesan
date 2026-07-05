@extends('layouts.app')
@section('title', 'Kelola Menu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Kelola Menu</h4>
        <p class="text-muted small mb-0">Manajemen menu pedesan sapi & kambing</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i>Tambah Menu
    </button>
</div>

{{-- Search & Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <div class="row g-2">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari nama menu...">
                </div>
            </div>
            <div class="col-md-3">
                <select id="filterKategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    <option value="sapi">Sapi</option>
                    <option value="kambing">Kambing</option>
                    <option value="mieinstan">Mi Instan</option>
                    <option value="minuman">Minuman</option>
                </select>
            </div>
            <div class="col-md-3 text-md-end">
                <small class="text-muted" id="rowCount">{{ $menus->count() }} menu</small>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Menu --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:1.25rem;">#</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-center" style="padding-right:1.25rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $i => $m)
                    <tr class="menu-row"
                        data-nama="{{ strtolower($m->nama_menu) }}"
                        data-kategori="{{ $m->kategori }}">
                        <td style="padding-left:1.25rem;color:#94a3b8;">{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $m->nama_menu }}</td>
                        <td>
                            <span class="badge" style="background:#e8f0fe;color:#1a56db;">
                                {{ ucfirst($m->kategori) }}
                            </span>
                        </td>
                        <td class="fw-semibold" style="color:#1a56db;">
                            Rp{{ number_format($m->harga, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($m->stok <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($m->stok <= 5)
                                <span class="badge bg-warning text-dark">{{ $m->stok }} porsi ⚠️</span>
                            @else
                                <span class="text-success fw-semibold">{{ $m->stok }} porsi</span>
                            @endif
                        </td>
                        <td class="text-center" style="padding-right:1.25rem;">
                            <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalHapus"
                                data-menu-id="{{ $m->id }}"
                                data-menu-name="{{ $m->nama_menu }}">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-journal-x"></i>
                            <p class="fw-semibold mb-1">Belum ada menu</p>
                            <button class="btn btn-sm btn-primary mt-1" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                Tambah Menu
                            </button>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah Menu --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:14px;">
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold">Tambah Menu Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" name="nama_menu" class="form-control"
                               placeholder="Pedesan Balungan Sapi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select" required>
                            <option value="" disabled selected>-- Pilih --</option>
                            <option value="sapi">🐄 Sapi</option>
                            <option value="kambing">🐐 Kambing</option>
                            <option value="mieinstan">🍜 Mi Instan</option>
                            <option value="minuman">🥤 Minuman</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" id="hargaInput" class="form-control"
                                   placeholder="35000" min="0" required>
                        </div>
                        <div class="form-text" id="hargaPreview"></div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Stok Awal (Porsi) <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control"
                               placeholder="20" min="0" required>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 gap-2">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0" style="border-radius:14px;">
            <div class="modal-body text-center py-4 px-4">
                <div class="mb-2" style="font-size:2.25rem;">🗑️</div>
                <h6 class="fw-bold mb-1">Hapus Menu?</h6>
                <p class="text-muted small mb-0">
                    Menu "<strong id="hapusNama"></strong>" akan dihapus permanen.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4 gap-2 justify-content-center">
                <button type="button" class="btn btn-light btn-sm px-4" data-bs-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-4">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Search & filter
    const si = document.getElementById('searchInput');
    const fk = document.getElementById('filterKategori');
    const rc = document.getElementById('rowCount');
    function filterTable() {
        const q = si.value.toLowerCase(), k = fk.value;
        let c = 0;
        document.querySelectorAll('.menu-row').forEach(r => {
            const show = r.dataset.nama.includes(q) && (!k || r.dataset.kategori === k);
            r.style.display = show ? '' : 'none';
            if (show) c++;
        });
        rc.textContent = c + ' menu';
    }
    si.addEventListener('input', filterTable);
    fk.addEventListener('change', filterTable);

    // Harga preview di modal tambah
    document.getElementById('hargaInput').addEventListener('input', function () {
        const v = parseInt(this.value);
        document.getElementById('hargaPreview').textContent = v > 0 ? 'Harga: Rp' + v.toLocaleString('id-ID') : '';
    });

    // Modal hapus
    document.getElementById('modalHapus').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('hapusNama').textContent = btn.dataset.menuName;
        document.getElementById('formHapus').action = `/admin/menus/${btn.dataset.menuId}`;
    });
</script>
@endpush