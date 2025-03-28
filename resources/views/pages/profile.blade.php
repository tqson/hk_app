@extends('layouts.app')
@include('partials.profile-style')
@section('title', 'Thông tin cá nhân - HK LOVE')
@section('page-title', 'Thông tin cá nhân')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-avatar-container mb-3">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Admin User') . '&background=random&size=200' }}"
                             alt="User Avatar" class="profile-avatar">
                    </div>
                    <h4 class="mt-2 mb-1">{{ Auth::user()->name }}</h4>
                    {{--                    <p class="text-muted">{{ Auth::user()->role ?? 'Administrator' }}</p>--}}
                    <!-- Avatar upload button - đã chuyển xuống dưới và làm đẹp hơn -->
                    <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" id="avatar-form" class="mb-3">
                        @csrf
                        @method('PUT')
                        <label for="avatar" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-camera me-1"></i> Thay đổi ảnh đại diện
                        </label>
                        <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                    </form>
                    <div class="profile-stats">
                        <div class="row">
                            <div class="col-4">
                                <div class="profile-stat-value">{{ $daysActive ?? 0 }}</div>
                                <div class="profile-stat-label">Ngày hoạt động</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Mã QR Thanh Toán</h5>
                </div>
                <div class="card-body text-center">
                    <div class="qr-code-container mb-3">
                        @if(Auth::user()->qr_code_image)
                            <img src="{{ Storage::url(Auth::user()->qr_code_image) }}" alt="QR Code" class="qr-code-image">
                        @else
                            <div class="qr-code-placeholder">
                                <i class="fas fa-qrcode"></i>
                                <p>Chưa có mã QR</p>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('profile.qrcode.update') }}" method="POST" enctype="multipart/form-data" id="qrcode-form" class="mb-3">
                        @csrf
                        @method('PUT')
                        <label for="qr_code_image" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-upload me-1"></i> {{ Auth::user()->qr_code_image ? 'Cập nhật mã QR' : 'Tải lên mã QR' }}
                        </label>
                        <input type="file" name="qr_code_image" id="qr_code_image" class="d-none" accept="image/*" onchange="document.getElementById('qrcode-form').submit()">
                    </form>

                    <div class="bank-info mt-3">
                        <div class="mb-2">
                            <small class="text-muted">Ngân hàng:</small>
                            <div>{{ Auth::user()->bank_name ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <small class="text-muted">Số tài khoản:</small>
                            <div>{{ Auth::user()->bank_account_number ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <!-- Display success message if any -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <!-- Display validation errors if any -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', Auth::user()->full_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', Auth::user()->address) }}</textarea>
                        </div>

                        <!-- Bank Information -->
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Tên ngân hàng</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', Auth::user()->bank_name) }}">
                        </div>
                        <div class="mb-3">
                            <label for="bank_account_number" class="form-label">Số tài khoản</label>
                            <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', Auth::user()->bank_account_number) }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                    </form>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <button class="btn toggle-password" type="button" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn  toggle-password" type="button" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <button class="btn  toggle-password" type="button" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Profile page specific styles */
        .profile-avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }
        .profile-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-avatar-upload {
            position: absolute;
            bottom: 0;
            right: 0;
        }
        .profile-avatar-upload .btn {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .profile-stats {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
        .profile-stat-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        .profile-stat-label {
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* QR Code styles */
        .qr-code-container {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9f9f9;
        }

        .qr-code-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .qr-code-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #aaa;
        }

        .qr-code-placeholder i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .qr-code-placeholder p {
            margin: 0;
            font-size: 0.9rem;
        }

        .bank-info {
            text-align: left;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        /* Password toggle button */
        .toggle-password {
            cursor: pointer;
        }

        .toggle-password:focus {
            box-shadow: none;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const toggleButtons = document.querySelectorAll('.toggle-password');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const inputField = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        inputField.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
@endsection

