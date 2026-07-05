@extends('layouts.app')

@section('page-title', 'Tambah Menu')
@section('breadcrumb', 'Menu Baru')

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

                <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small">NAMA MENU</label>
                        <input type="text" name="nama_menu" class="form-control bg-light border-0" value="{{ old('nama_menu') }}" placeholder="Pedesan Iga Sapi" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">KATEGORI</label>
                        <select name="kategori" class="form-select bg-light border-0" required>
                            <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>-- Pilih Kategori --</option>
                            <option value="sapi" {{ old('kategori') == 'sapi' ? 'selected' : '' }}>Daging Sapi</option>
                            <option value="kambing" {{ old('kategori') == 'kambing' ? 'selected' : '' }}>Daging Kambing</option>
                            <option value="mieinstan" {{ old('kategori') == 'mieinstan' ? 'selected' : '' }}>Mi Instan</option>
                            <option value="minuman" {{ old('kategori') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">HARGA (RP)</label>
                            <input type="number" name="harga" class="form-control bg-light border-0" value="{{ old('harga') }}" placeholder="45000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">STOK AWAL</label>
                            <input type="number" name="stok" class="form-control bg-light border-0" value="{{ old('stok') }}" placeholder="20" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">UPLOAD FOTO MENU (OPSIONAL)</label>
                        <input type="file" name="gambar" accept="image/*" class="form-control bg-light border-0">
                        <small class="text-muted small mt-1 d-block">JPG/PNG/WEBP, maks 2MB. Disimpan ke storage server.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">ATAU URL FOTO MENU (OPSIONAL)</label>
                        <input type="url" name="gambar_url" class="form-control bg-light border-0" value="{{ old('gambar_url') }}" placeholder="https://image.com/foto.jpg">
                        <small class="text-muted small mt-1 d-block">Dipakai kalau tidak upload file. Kalau dua-duanya diisi, foto upload yang diprioritaskan.</small>
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Menu</button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-light px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection