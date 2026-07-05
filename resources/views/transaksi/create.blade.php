@extends('layouts.app')

@section('page-title', 'Kasir - Input Pesanan')
@section('breadcrumb', 'Pedesan Sapi & Kambing')

@section('content')
<form action="{{ route('kasir.transaksi.store') }}" method="POST" id="mainForm">
    @csrf
    <div class="row g-4">
        {{-- Sisi Kiri: Nama Pelanggan & Katalog Menu (2 Kolom per Baris) --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Nama Pelanggan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-person-fill text-primary"></i></span>
                        <input type="text" name="nama_pelanggan" class="form-control form-control-lg bg-light border-0" 
                               placeholder="Masukkan nama pembeli..." required>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-grid-fill text-primary"></i> Katalog Menu
            </h5>

            {{-- Grid Menu: Diatur menjadi col-6 agar selalu 2 menu per baris --}}
            <div class="row g-3">
                @forelse($menus as $m)
                <div class="col-6">
                    <div class="card h-100 border-0 shadow-sm menu-item-card" 
                         style="cursor:pointer; border-radius: 18px; overflow: hidden; transition: 0.3s;" 
                         onclick="addToCart({{ $m->id }}, '{{ $m->nama_menu }}', {{ $m->harga }}, {{ $m->stok }}, '{{ $m->gambar_tampil }}')">
                        
                        <div class="position-relative">
                            <img src="{{ $m->gambar_tampil ?? 'https://placehold.co/400x300?text=No+Image' }}" 
                                 class="card-img-top" style="height:140px; object-fit:cover;">
                            <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75 shadow-sm">
                                Rp{{ number_format($m->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-1 text-dark text-truncate" title="{{ $m->nama_menu }}">{{ $m->nama_menu }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background: var(--primary-light); color: var(--primary); font-size: 0.65rem;">
                                    {{ ucfirst($m->kategori) }}
                                </span>
                                <small class="text-muted" style="font-size:0.7rem">Stok: {{ $m->stok }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-patch-exclamation fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Menu tidak tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Sisi Kanan: Keranjang Belanja dengan Kontrol Qty --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm sticky-top" style="top:90px; border-radius:20px;">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="fw-bold mb-0 d-flex justify-content-between align-items-center">
                        Keranjang <span><i class="bi bi-cart3 text-primary"></i></span>
                    </h5>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 450px;">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small fw-bold">
                                    <th class="ps-4 py-3">ITEM</th>
                                    <th class="py-3 text-center">QTY</th>
                                    <th class="py-3">SUBTOTAL</th>
                                    <th class="pe-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-table">
                                {{-- Item akan muncul di sini --}}
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="empty-cart" class="text-center py-5 text-muted">
                        <i class="bi bi-basket2 fs-1 d-block mb-2"></i>
                        <p class="small fw-bold">Pilih menu untuk memulai pesanan</p>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-3">
                        <span class="fw-bold text-secondary small text-uppercase">Total Bayar</span>
                        <h3 class="fw-bold text-primary mb-0" id="grand-total">Rp0</h3>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow" id="btn-submit" disabled>
                        PROSES PESANAN <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .menu-item-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
    
    /* Perbaikan CSS untuk visibilitas angka Quantity */
    .qty-input {
        width: 35px !important;
        padding: 0 !important;
        font-size: 1rem;
        background: transparent !important;
        border: none !important;
        text-align: center;
        font-weight: 700;
        color: #334155;
        outline: none !important;
        box-shadow: none !important;
    }
    
    .qty-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px !important;
        transition: 0.2s;
    }
    
    .qty-btn:hover {
        background-color: var(--primary) !important;
        color: white !important;
    }

    .qty-btn i:hover {
        color: white !important;
    }
</style>
@endsection

@push('scripts')
<script>
let cart = [];

function addToCart(id, name, price, stok, img) {
    const existingIndex = cart.findIndex(item => item.menu_id === id);
    if (existingIndex !== -1) {
        // Jika sudah ada, tambahkan qty (maksimal sesuai stok)
        updateQty(existingIndex, 1);
    } else {
        // Jika baru, masukkan ke array
        cart.push({ 
            menu_id: id, 
            nama: name, 
            harga: price, 
            jumlah: 1, 
            stok: stok 
        });
        renderCart();
    }
}

function updateQty(index, delta) {
    const item = cart[index];
    const newVal = item.jumlah + delta;
    
    if (newVal >= 1 && newVal <= item.stok) {
        item.jumlah = newVal;
        renderCart();
    } else if (newVal > item.stok) {
        alert('Stok porsi ' + item.nama + ' tidak mencukupi!');
    }
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

function renderCart() {
    const table = document.getElementById('cart-table');
    const empty = document.getElementById('empty-cart');
    const btn = document.getElementById('btn-submit');
    const totalDisplay = document.getElementById('grand-total');
    
    table.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        empty.classList.remove('d-none');
        btn.disabled = true;
    } else {
        empty.classList.add('d-none');
        btn.disabled = false;
        
        cart.forEach((item, index) => {
            const subtotal = item.harga * item.jumlah;
            total += subtotal;
            
            table.innerHTML += `
                <tr class="border-bottom border-light">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">${item.nama}</div>
                        <div class="text-muted" style="font-size:0.7rem">@ Rp${item.harga.toLocaleString('id-ID')}</div>
                        <input type="hidden" name="items[${index}][menu_id]" value="${item.menu_id}">
                    </td>
                    <td class="py-3">
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-pill p-1 shadow-sm" style="width: fit-content; margin: 0 auto;">
                            <button type="button" class="btn btn-sm btn-white bg-white qty-btn" onclick="updateQty(${index}, -1)">
                                <i class="bi bi-dash text-primary"></i>
                            </button>
                            <!-- Menggunakan type="text" untuk menghindari spinner browser yang menghalangi teks -->
                            <input type="text" name="items[${index}][jumlah]" value="${item.jumlah}" 
                                   class="qty-input" readonly>
                            <button type="button" class="btn btn-sm btn-white bg-white qty-btn" onclick="updateQty(${index}, 1)">
                                <i class="bi bi-plus text-primary"></i>
                            </button>
                        </div>
                    </td>
                    <td class="py-3 fw-bold text-dark small">Rp${subtotal.toLocaleString('id-ID')}</td>
                    <td class="pe-4 py-3 text-end">
                        <button type="button" class="btn btn-link text-danger p-0" onclick="removeFromCart(${index})">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    totalDisplay.textContent = 'Rp' + total.toLocaleString('id-ID');
}
</script>
@endpush