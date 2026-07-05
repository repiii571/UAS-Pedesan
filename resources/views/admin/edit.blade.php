@extends('layouts.app')

@section('page-title', 'Edit Menu')
@section('breadcrumb', $menu->nama_menu)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    <div class="text-center mb-4">
                        <img src="{{ $menu->gambar_tampil ?? 'https://placehold.co/150x150?text=No+Image' }}" 
                             class="rounded-4 shadow-sm mb-3" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 class="fw-bold mb-0">Update Informasi Menu</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">NAMA MENU</label>
                        <input type="text" name="nama_menu" class="form-control bg-light border-0" value="{{ $menu->nama_menu }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">KATEGORI</label>
                        <select name="kategori" class="form-select bg-light border-0" required>
                            <option value="sapi" {{ $menu->kategori == 'sapi' ? 'selected' : '' }}>Daging Sapi</option>
                            <option value="kambing" {{ $menu->kategori == 'kambing' ? 'selected' : '' }}>Daging Kambing</option>
                            <option value="mieinstan" {{ $menu->kategori == 'mieinstan' ? 'selected' : '' }}>Mi Instan</option>
                            <option value="minuman" {{ $menu->kategori == 'minuman' ? 'selected' : '' }}>Minuman</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">HARGA (RP)</label>
                            <input type="number" name="harga" class="form-control bg-light border-0" value="{{ $menu->harga }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">STOK</label>
                            <input type="number" name="stok" class="form-control bg-light border-0" value="{{ $menu->stok }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">GANTI FOTO (UPLOAD, OPSIONAL)</label>
                        <input type="file" name="gambar" accept="image/*" class="form-control bg-light border-0">
                        <small class="text-muted small mt-1 d-block">Kosongkan kalau tidak mau ganti foto. JPG/PNG/WEBP, maks 2MB.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">ATAU URL FOTO MENU</label>
                        <input type="url" name="gambar_url" class="form-control bg-light border-0" value="{{ $menu->gambar_url }}">
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Perubahan</button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-light px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection