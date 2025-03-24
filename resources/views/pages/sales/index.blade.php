@extends('layouts.app')

@section('styles')
    <style>
        .product-search-results {
            position: absolute;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: none;
        }

        .product-search-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .product-search-item:hover {
            background-color: #f8f9fa;
        }

        .cart-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .payment-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }

        .qr-code-modal .modal-body {
            text-align: center;
        }

        .qr-code-image {
            max-width: 100%;
            height: auto;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Bán hàng</h1>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group position-relative">
                            <label for="productSearch">Thêm sản phẩm vào đơn</label>
                            <input type="text" class="form-control" id="productSearch" placeholder="Gõ tên sản phẩm cần tìm...">
                            <div class="product-search-results" id="searchResults"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Bảng danh sách sản phẩm -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="cart-table">
                            <table class="table table-bordered table-hover" id="cartTable">
                                <thead>
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="25%">Tên sản phẩm</th>
                                    <th width="10%">Đơn vị</th>
                                    <th width="10%">Tồn kho</th>
                                    <th width="10%">Số lượng</th>
                                    <th width="10%">Số lô</th>
                                    <th width="15%">Đơn giá</th>
                                    <th width="15%">Thành tiền</th>
                                    <th width="5%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Dữ liệu sẽ được thêm bằng JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Khu vực tính tiền -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Thanh toán</h5>
                    </div>
                    <div class="card-body payment-section">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Tổng tiền:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control-plaintext text-right font-weight-bold" id="totalAmount" readonly value="0 VNĐ">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Chiết khấu:</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" id="discount" value="0" min="0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Khách phải trả:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control-plaintext text-right font-weight-bold" id="finalAmount" readonly value="0 VNĐ">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Tiền mặt:</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" id="cashAmount" value="0" min="0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Chuyển khoản:</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" id="transferAmount" value="0" min="0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Tiền thừa:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control-plaintext text-right" id="changeAmount" readonly value="0 VNĐ">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Ghi chú:</label>
                            <textarea class="form-control" id="notes" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-info" id="qrCodeBtn">
                                <i class="fas fa-qrcode"></i> QR Code
                            </button>
                            <button type="button" class="btn btn-primary" id="paymentBtn">
                                <i class="fas fa-check"></i> Thanh toán
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @csrf
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade qr-code-modal" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel">Quét mã QR để thanh toán</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($user && $user->qr_code_image)
                        <img src="{{ asset('storage/' . $user->qr_code_image) }}" alt="QR Code" class="qr-code-image">
                    @else
                        <p class="text-center">Bạn chưa cập nhật mã QR trong thông tin cá nhân.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Huỷ</button>
                    <button type="button" class="btn btn-primary" id="qrPaymentBtn">Thanh toán</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let cart = [];
            let totalAmount = 0;

            // Tìm kiếm sản phẩm
            $('#productSearch').on('input', function() {
                const query = $(this).val();
                if (query.length >= 2) {
                    $.ajax({
                        url: "{{ route('sales.search-products') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            let html = '';
                            if (data.length > 0) {
                                data.forEach(product => {
                                    html += `<div class="product-search-item" data-id="${product.id}">
                                    ${product.name} - ${product.stock} ${product.unit} (Lô: ${product.batch_number})
                                </div>`;
                                });
                            } else {
                                html = '<div class="p-3">Không tìm thấy sản phẩm</div>';
                            }
                            $('#searchResults').html(html).show();
                        }
                    });
                } else {
                    $('#searchResults').hide();
                }
            });

            // Chọn sản phẩm từ kết quả tìm kiếm
            $(document).on('click', '.product-search-item', function() {
                const productId = $(this).data('id');

                $.ajax({
                    url: `/sales/get-product/${productId}`,
                    method: 'GET',
                    success: function(product) {
                        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
                        const existingProduct = cart.find(item => item.id === product.id);

                        if (existingProduct) {
                            // Nếu đã có, tăng số lượng
                            if (existingProduct.quantity < product.stock) {
                                existingProduct.quantity += 1;
                                updateCartTable();
                            } else {
                                alert('Số lượng đã đạt tối đa tồn kho!');
                            }
                        } else {
                            // Nếu chưa có, thêm mới vào giỏ hàng với giá cố định
                            cart.push({
                                id: product.id,
                                name: product.name,
                                unit: product.unit,
                                stock: product.stock,
                                batch_number: product.batch_number,
                                price: product.price || 0,  // Sử dụng giá cố định từ database
                                quantity: 1
                            });
                            updateCartTable();
                        }

                        // Xóa kết quả tìm kiếm và reset ô input
                        $('#searchResults').hide();
                        $('#productSearch').val('');
                    }
                });
            });

            // Cập nhật bảng giỏ hàng
            function updateCartTable() {
                let html = '';
                totalAmount = 0;

                cart.forEach((item, index) => {
                    const subtotal = item.price * item.quantity;
                    totalAmount += subtotal;

                    html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>${item.unit}</td>
                    <td>${item.stock}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input"
                            data-index="${index}" value="${item.quantity}" min="1" max="${item.stock}">
                    </td>
                    <td>${item.batch_number}</td>
                    <td>
                        <span class="price-display">${item.price}</span>
                    </td>
                    <td>${formatCurrency(subtotal)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
                });

                $('#cartTable tbody').html(html);
                updatePaymentSection();
            }

            // Cập nhật phần thanh toán
            function updatePaymentSection() {
                $('#totalAmount').val(formatCurrency(totalAmount));

                const discount = parseFloat($('#discount').val()) || 0;
                const finalAmount = totalAmount - discount;
                $('#finalAmount').val(formatCurrency(finalAmount));

                // Tính tiền thừa
                calculateChange();
            }

            // Định dạng tiền tệ
            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
            }

            // Xử lý khi thay đổi số lượng
            $(document).on('change', '.quantity-input', function() {
                const index = $(this).data('index');
                const newQuantity = parseInt($(this).val());

                if (newQuantity > 0 && newQuantity <= cart[index].stock) {
                    cart[index].quantity = newQuantity;
                    updateCartTable();
                } else {
                    alert('Số lượng không hợp lệ!');
                    $(this).val(cart[index].quantity);
                }
            });

            // Xử lý khi thay đổi đơn giá
            // $(document).on('change', '.price-input', function() {
            //     const index = $(this).data('index');
            //     const newPrice = parseFloat($(this).val());
            //
            //     if (newPrice >= 0) {
            //         cart[index].price = newPrice;
            //         updateCartTable();
            //     } else {
            //         alert('Đơn giá không hợp lệ!');
            //         $(this).val(cart[index].price);
            //     }
            // });

            // Xử lý khi xóa sản phẩm
            $(document).on('click', '.remove-item', function() {
                const index = $(this).data('index');
                cart.splice(index, 1);
                updateCartTable();
            });

            // Xử lý khi thay đổi chiết khấu
            $('#discount').on('change', function() {
                updatePaymentSection();
            });

            // Xử lý khi nhập tiền mặt
            $('#cashAmount').on('input', function() {
                const cashAmount = parseFloat($(this).val()) || 0;

                if (cashAmount > 0) {
                    $('#transferAmount').val(0).prop('disabled', true);
                    $('#qrCodeBtn').prop('disabled', true);
                } else {
                    $('#transferAmount').prop('disabled', false);
                    $('#qrCodeBtn').prop('disabled', false);
                }

                calculateChange();
            });

            // Xử lý khi nhập tiền chuyển khoản
            $('#transferAmount').on('input', function() {
                const transferAmount = parseFloat($(this).val()) || 0;

                if (transferAmount > 0) {
                    $('#cashAmount').val(0).prop('disabled', true);
                    $('#paymentBtn').prop('disabled', false);
                } else {
                    $('#cashAmount').prop('disabled', false);
                }

                calculateChange();
            });

            // Tính tiền thừa
            function calculateChange() {
                console.log('calculateChange called');
                const finalAmount = totalAmount - (parseFloat($('#discount').val()) || 0);
                const cashAmount = parseFloat($('#cashAmount').val()) || 0;
                const transferAmount = parseFloat($('#transferAmount').val()) || 0;

                // If using cash payment
                if (cashAmount > 0) {
                    const change = cashAmount - finalAmount;
                    $('#changeAmount').val(formatCurrency(change >= 0 ? change : 0));

                    // Enable payment button only if cash is enough
                    $('#paymentBtn').prop('disabled', change < 0);
                }
                // If using transfer payment
                else if (transferAmount > 0) {
                    console.log('else')
                    const change = transferAmount - finalAmount;
                    $('#changeAmount').val(formatCurrency(change >= 0 ? change : 0));

                    // Enable payment button only if transfer amount is enough
                    $('#paymentBtn').prop('disabled', transferAmount < finalAmount);
                }
                // No payment method has a value
                else {
                    $('#changeAmount').val(formatCurrency(0));
                    $('#paymentBtn').prop('disabled', true);
                }
            }

            // Xử lý khi click nút QR Code
            $('#qrCodeBtn').on('click', function() {
                if (cart.length === 0) {
                    alert('Vui lòng thêm sản phẩm vào đơn hàng!');
                    return;
                }

                const finalAmount = totalAmount - (parseFloat($('#discount').val()) || 0);
                if (finalAmount <= 0) {
                    alert('Tổng tiền phải lớn hơn 0!');
                    return;
                }

                $('#qrCodeModal').modal('show');
            });

            // Xử lý khi click nút Thanh toán trong modal QR
            $('#qrPaymentBtn').on('click', function() {
                $('#transferAmount').val(totalAmount - (parseFloat($('#discount').val()) || 0));
                $('#cashAmount').val(0).prop('disabled', true);
                $('#qrCodeModal').modal('hide');

                // Thực hiện thanh toán
                processPayment();
            });

            // Xử lý khi click nút Thanh toán
            $('#paymentBtn').on('click', function() {
                if (cart.length === 0) {
                    alert('Vui lòng thêm sản phẩm vào đơn hàng!');
                    return;
                }

                const finalAmount = totalAmount - (parseFloat($('#discount').val()) || 0);
                if (finalAmount <= 0) {
                    alert('Tổng tiền phải lớn hơn 0!');
                    return;
                }

                // Kiểm tra phương thức thanh toán
                const cashAmount = parseFloat($('#cashAmount').val()) || 0;
                const transferAmount = parseFloat($('#transferAmount').val()) || 0;

                if (cashAmount <= 0 && transferAmount <= 0) {
                    alert('Vui lòng nhập số tiền thanh toán!');
                    return;
                }

                if (cashAmount > 0 && cashAmount < finalAmount) {
                    alert('Số tiền khách đưa không đủ!');
                    return;
                }

                // Thực hiện thanh toán
                processPayment();
            });

            // Xử lý thanh toán
            function processPayment() {
                console.log($('meta[name="csrf-token"]').attr('content'))
                const finalAmount = totalAmount - (parseFloat($('#discount').val()) || 0);
                const cashAmount = parseFloat($('#cashAmount').val()) || 0;
                const transferAmount = parseFloat($('#transferAmount').val()) || 0;
                const changeAmount = cashAmount > finalAmount ? cashAmount - finalAmount : 0;

                // Chuẩn bị dữ liệu để gửi lên server
                const data = {
                    products: cart.map(item => ({
                        id: item.id,
                        quantity: item.quantity,
                        price: item.price
                    })),
                    total_amount: totalAmount,
                    discount: parseFloat($('#discount').val()) || 0,
                    final_amount: finalAmount,
                    cash_amount: cashAmount,
                    transfer_amount: transferAmount,
                    change_amount: changeAmount,
                    notes: $('#notes').val()
                };

                // Gửi request tạo hóa đơn
                $.ajax({
                    url: "{{ route('sales.create-invoice') }}",
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: response.message,
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Xem danh sách hóa đơn',
                                cancelButtonText: 'Tiếp tục bán hàng'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('sales.invoices') }}";
                                } else {
                                    // Reset form và giỏ hàng
                                    resetForm();
                                }
                            });
                        } else {
                            Swal.fire('Lỗi!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        toastr.error('Có lỗi xảy ra khi tạo hóa đơn!');
                    }
                });
            }

            // Đóng kết quả tìm kiếm khi click ra ngoài
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearch, #searchResults').length) {
                    $('#searchResults').hide();
                }
            });
        });
    </script>
@endsection
