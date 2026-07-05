@extends('layouts.app')

@section('page-title', 'Kelola Akun')
@section('breadcrumb', 'Akun Admin & Kasir')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Akun</h4>
        <p class="text-muted small mb-0">Daftar akun admin dan kasir yang bisa login ke sistem.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-person-plus-fill"></i> Tambah Akun
    </a>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-secondary small fw-bold">
                        <th class="ps-4 py-3">NAMA</th>
                        <th class="py-3">EMAIL</th>
                        <th class="py-3">ROLE</th>
                        <th class="py-3">BERGABUNG</th>
                        <th class="py-3 pe-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="background:#e8f0fe;color:var(--primary);width:32px;height:32px;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span class="fw-bold">{{ $u->name }}</span>
                                @if($u->id === auth()->id())
                                    <span class="badge bg-light text-secondary border">Kamu</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge {{ $u->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $u->created_at->format('d/m/Y') }}</td>
                        <td class="pe-4 text-center">
                            @if($u->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            Belum ada akun terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
