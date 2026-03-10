<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MedTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #1a6f8a;
            --primary-dark: #145a72;
            --sidebar-bg: #0d2137;
        }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d2137 0%, #1a365d 50%, #0d2137 100%);
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 30%, rgba(26,111,138,0.08) 0%, transparent 50%),
                        radial-gradient(circle at 70% 70%, rgba(26,111,138,0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-2%, -2%); }
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.4);
            overflow: hidden;
            max-width: 440px;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, #0f5a70 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        }
        .login-header i {
            font-size: 56px;
            margin-bottom: 12px;
            display: inline-block;
            animation: pulse 2s ease-in-out infinite;
            color: #fff;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .brand-name {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #fff;
        }
        .login-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 4px;
            color: #fff;
        }
        .login-hospital {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 8px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #fff;
        }
        .login-body {
            padding: 36px;
        }
        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 12px;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            font-size: 14px;
            transition: all 0.25s ease;
            background: #f9fafb;
        }
        .form-control:hover {
            border-color: #d1d5db;
            background: #fff;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(26,111,138,0.1);
            background: #fff;
            outline: none;
        }
        .input-group-text {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px 0 0 12px;
            border-right: none;
            color: #9ca3af;
            padding: 14px 16px;
        }
        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }
        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            color: var(--primary);
        }
        .input-group:focus-within .form-control {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(26,111,138,0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 700;
            font-size: 15px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26,111,138,0.35);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26,111,138,0.45);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
        .login-footer-text {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 8px;
        }
        .login-credentials {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 12px;
            color: #6b7280;
        }
        .login-credentials code {
            background: #e0e7ff;
            color: var(--primary);
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .invalid-feedback {
            font-size: 12px;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-notes-medical"></i>
            <div class="brand-name">MedTrack</div>
            <div class="login-subtitle">Sistem Rekam Medis</div>
            <div class="login-hospital">Klinik Pratama Rawat Inap Husada</div>
        </div>
        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               placeholder="email@husada-clinic.id" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Masukkan password" required>
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Sistem
                </button>
            </form>
            <div class="login-footer">
                <div class="login-footer-text">Default Akun Demo</div>
                <div class="login-credentials">
                    <code>admin@husada-clinic.id</code> / <code>password</code>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
