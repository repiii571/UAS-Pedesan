@extends('layouts.app')

@section('page-title', 'Tambah Akun')
@section('breadcrumb', 'Akun Baru')

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

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small">NAMA</label>
                        <input type="text" name="name" class="form-control bg-light border-0"
                               value="{{ old('name') }}" placeholder="Nama lengkap" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">EMAIL</label>
                        <input type="email" name="email" class="form-control bg-light border-0"
                               value="{{ old('email') }}" placeholder="user@mail.com" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">PASSWORD</label>
                            <input type="password" name="password" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">ULANGI PASSWORD</label>
                            <input type="password" name="password_confirmation" class="form-control bg-light border-0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">ROLE</label>
                        <select name="role" class="form-select bg-light border-0" required>
                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Akun</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
