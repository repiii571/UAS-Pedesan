<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Warung Pedesan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh;background:linear-gradient(135deg,#0f2d6b 0%,#1a56db 60%,#3b82f6 100%);display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',system-ui,sans-serif; }
        .login-wrapper { width:100%;max-width:420px;padding:1rem; }
        .login-brand { text-align:center;margin-bottom:2rem; }
        .login-brand .brand-icon { width:70px;height:70px;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.2);border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 1rem;backdrop-filter:blur(10px); }
        .login-brand h1 { color:#fff;font-size:1.5rem;font-weight:800;margin-bottom:.2rem; }
        .login-brand p { color:rgba(255,255,255,.55);font-size:.85rem; }
        .login-card { background:rgba(255,255,255,.97);border-radius:18px;padding:2.25rem;box-shadow:0 20px 50px rgba(0,0,0,.3); }
        .login-card h5 { font-weight:700;color:#1e293b;margin-bottom:1.5rem;font-size:1.05rem; }
        .form-label { font-size:.83rem;font-weight:600;color:#475569;margin-bottom:.35rem; }
        .input-group-text { background:#f8faff;border-color:#d1d9f0;color:#6b7280;border-right:none; }
        .form-control { border-left:none;border-radius:0 9px 9px 0 !important;border-color:#d1d9f0;padding:.62rem 1rem;font-size:.9rem; }
        .input-group .input-group-text { border-radius:9px 0 0 9px; }
        .form-control:focus { border-color:#1a56db;box-shadow:0 0 0 3px rgba(26,86,219,.12); }
        .btn-login { background:linear-gradient(135deg,#1a56db,#3b82f6);border:none;border-radius:11px;padding:.72rem;font-weight:700;font-size:.95rem;box-shadow:0 4px 15px rgba(26,86,219,.35);transition:all .2s; }
        .btn-login:hover { transform:translateY(-1px);box-shadow:0 8px 20px rgba(26,86,219,.4); }
        .password-toggle { cursor:pointer;border-left:none !important;border-color:#d1d9f0 !important;border-radius:0 9px 9px 0 !important;background:#f8faff !important;color:#6b7280 !important; }
        .password-field { border-right:none !important;border-radius:0 !important; }
        .footer-text { text-align:center;margin-top:1.5rem;color:rgba(255,255,255,.35);font-size:.75rem; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-brand">
        <div class="brand-icon">🍖</div>
        <h1>Warung Pedesan</h1>
        <p>Sistem Manajemen Penjualan</p>
    </div>
    <div class="login-card">
        <h5><i class="bi bi-door-open me-2 text-primary"></i>Masuk ke Sistem</h5>
        @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3 py-2" style="border-radius:9px;font-size:.84rem;">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="----@mail.com" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="passwordInput"
                           class="form-control password-field" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePassword">
                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
            </button>
        </form>
    </div>
    <p class="footer-text">© {{ date('Y') }} Warung Pedesan Sapi & Kambing</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const inp = document.getElementById('passwordInput');
        const ico = document.getElementById('eyeIcon');
        if (inp.type === 'password') { inp.type='text'; ico.className='bi bi-eye-slash-fill'; }
        else { inp.type='password'; ico.className='bi bi-eye-fill'; }
    });
</script>
</body>
</html>
