<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu - HK LOVE</title>
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

        .reset-container {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .reset-title {
            text-align: center;
            margin-bottom: 40px;
            color: rgba(0, 0, 0, 0.85);
        }

        .reset-title h1 {
            font-size: 33px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .reset-title p {
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

        .alert-success {
            border-radius: var(--border-radius);
            border-color: #b7eb8f;
            background-color: #f6ffed;
            color: #52c41a;
        }

        .alert-danger {
            border-radius: var(--border-radius);
            border-color: #ffccc7;
            background-color: #fff2f0;
            color: #ff4d4f;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .back-to-login a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .back-to-login a:hover {
            color: #40a9ff;
        }
    </style>
</head>
<body>
<div class="reset-container">
    <div class="reset-title">
        <h1>HK LOVE</h1>
        <p>Nhập email của bạn để đặt lại mật khẩu</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="mb-4">
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

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Gửi Link Đặt Lại Mật Khẩu</button>
        </div>
    </form>

    <div class="back-to-login">
        <a href="{{ route('login') }}">Quay lại đăng nhập</a>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
