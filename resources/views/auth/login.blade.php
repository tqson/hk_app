<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - HK LOVE</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS for Ant Design style -->
    <style>
        :root {
            --primary-color: #1890ff;
            --border-radius: 2px;
            --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .login-title {
            text-align: center;
            margin-bottom: 40px;
            color: rgba(0, 0, 0, 0.85);
        }

        .login-title h1 {
            font-size: 33px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .login-title p {
            font-size: 14px;
            color: rgba(0, 0, 0, 0.45);
        }

        .form-control {
            height: 40px;
            border-radius: var(--border-radius);
            border: 1px solid #d9d9d9;
            padding: 4px 11px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(24, 144, 255, 0.2);
        }

        .form-label {
            font-weight: 400;
            color: rgba(0, 0, 0, 0.85);
            font-size: 14px;
        }

        .input-group-text {
            background-color: transparent;
            border-radius: var(--border-radius);
            border-right: none;
        }

        .form-control.left-icon {
            border-left: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: var(--border-radius);
            height: 40px;
            font-weight: 400;
            font-size: 14px;
            box-shadow: none;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: #40a9ff;
            border-color: #40a9ff;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .forgot-password {
            color: var(--primary-color);
            font-size: 14px;
            text-decoration: none;
        }

        .forgot-password:hover {
            color: #40a9ff;
        }

        .alert-danger {
            border-radius: var(--border-radius);
            border-color: #ffccc7;
            background-color: #fff2f0;
            color: #ff4d4f;
        }
    </style>
</head>
<body>
    <div class="login-container">
    <div class="login-title">
        <h1>HK LOVE</h1>
        <p>Chào mừng quay trở lại! Vui lòng đăng nhập vào tài khoản của bạn.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                <input type="email" class="form-control left-icon @error('email') is-invalid @enderror"
                       id="email" name="email" placeholder="Nhập email của bạn"
                       value="{{ old('email') }}" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                <input type="password" class="form-control left-icon @error('password') is-invalid @enderror"
                       id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Ghi nhớ đăng nhập
                </label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password">
                    Quên mật khẩu?
                </a>
            @endif
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </div>
    </form>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
