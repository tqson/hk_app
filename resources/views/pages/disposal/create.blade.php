@extends('layouts.app')

@section('styles')
    <style>
        .card-dashboard {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.09);
            transition: all 0.3s;
            overflow: hidden;
            border: 1px solid #f0f0f0;
        }

        .card-dashboard:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: #1890ff;
            color: white;
            border-bottom: none;
            padding: 16px 24px;
            font-weight: 500;
        }

        .card-header .card-title {
            font-weight: 500;
            font-size: 16px;
            margin-bottom: 0;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: #fafafa;
            border-top: none;
            font-weight: 500;
            text-transform: none;
            font-size: 14px;
            padding: 16px 12px;
            color: rgba(0, 0, 0, 0.85);
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr {
            transition: all 0.3s;
        }

        .table tbody tr:hover {
            background-color: #e6f7ff;
        }

        .table td {
            vertical-align: middle;
            padding: 16px 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .search-product {
            position: relative;
            margin-bottom: 24px;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d9d9d9;
            border-radius: 0 0 4px 4px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .search-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-item:hover {
            background: #e6f7ff;
        }

        .search-item:last-child {
            border-bottom: none;
        }

        .search-item .product-name {
            font-weight: 500;
            color: rgba(0, 0, 0, 0.85);
        }

        .search-item .product-info {
            font-size: 13px;
            color: rgba(0, 0, 0, 0.45);
            margin-top: 4px;
        }

        .form-control, .batch-select {
            width: 100%;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #d9d9d9;
            transition: all 0.3s;
        }

        .form-control:hover, .batch-select:hover {
            border-color: #40a9ff;
        }

        .form-control:focus, .batch-select:focus {
            border-color: #40a9ff;
            box-shadow: 0 0 0 2px rgba(24, 144, 255, 0.2);
            outline: none;
        }

        .btn-remove {
            color: #ff4d4f;
            background: transparent;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .btn-remove:hover {
            color: #ff7875;
            background-color: rgba(255, 77, 79, 0.1);
        }

        .summary-box {
            background: #fafafa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 24px;
            border: 1px solid #f0f0f0;
        }

        .summary-title {
            font-weight: 500;
            font-size: 16px;
            margin-bottom: 16px;
            color: rgba(0, 0, 0, 0.85);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .summary-label {
            font-weight: normal;
            color: rgba(0, 0, 0, 0.65);
        }

        .summary-value {
            font-weight: 500;
            color: rgba(0, 0, 0, 0.85);
        }

        .total-amount {
            font-size: 18px;
            font-weight: 500;
            color: #1890ff;
        }

        .empty-cart {
            text-align: center;
            padding: 40px 0;
        }

        .empty-cart i {
            font-size: 48px;
            color: #d9d9d9;
            margin-bottom: 16px;
        }

        .empty-cart p {
            color: rgba(0, 0, 0, 0.45);
            font-size: 16px;
        }

        .btn-action {
            border-radius: 4px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 400;
            transition: all 0.3s;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-save {
            background-color: #1890ff;
            border-color: #1890ff;
            color: white;
        }

        .btn-save:hover {
            background-color: #40a9ff;
            border-color: #40a9ff;
        }

        .btn-back {
            background-color: white;
            border: 1px solid #d9d9d9;
            color: rgba(0, 0, 0, 0.65);
        }

        .btn-back:hover {
            color: #40a9ff;
            border-color: #40a9ff;
        }

        .btn-save:disabled {
            background-color: #f5f5f5;
            border-color: #d9d9d9;
            color: rgba(0, 0, 0, 0.25);
            cursor: not-allowed;
        }

        .input-quantity, .input-price {
            width: 100px;
            text-align: right;
        }

        .input-reason {
            width: 100%;
        }

        .batch-info {
            font-size: 12px;
            color: rgba(0, 0, 0, 0.45);
            margin-top: 4px;
        }

        .is-invalid {
            border-color: #ff4d4f !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 2px rgba(255, 77, 79, 0.2) !important;
        }

        /* Form label styling */
        label {
            font-weight: 500;
            color: rgba(0, 0, 0, 0.85);
            margin-bottom: 8px;
            display: block;
        }

        /* Improve spacing */
        .form-group {
            margin-bottom: 20px;
        }

        /* Improve table responsiveness */
        .table-responsive {
            border-radius: 8px;
            border: 1px solid #f0f0f0;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tạo phiếu xuất hủy</h1>
        </div>

        <form id="disposalForm" action="{{ route('disposal.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-9">
                    <div class="card card-dashboard shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-white">Thông tin sản phẩm hủy</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group search-product">
                                <label for="search_product">Tìm kiếm sản phẩm:</label>
                                <input type="text" id="search_product" class="form-control" placeholder="Nhập tên sản phẩm...">
                                <div class="search-results" style="display: none;"></div>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered" id="disposalItems">
                                    <thead>
                                    <tr>
                                        <th style="width: 50px">STT</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Lô - HSD</th>
                                        <th>ĐVT</th>
                                        <th>SL tồn</th>
                                        <th>SL hủy</th>
                                        <th>Giá hủy</th>
                                        <th>Lý do hủy</th>
                                        <th>Thành tiền</th>
                                        <th style="width: 50px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr id="empty-row">
                                        <td colspan="10">
                                            <div class="empty-cart">
                                                <i class="fas fa-box-open"></i>
                                                <p>Chưa có sản phẩm nào được thêm vào phiếu</p>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card card-dashboard shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-white">Thông tin phiếu hủy</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="disposal_code">Mã phiếu hủy:</label>
                                <input type="text" id="disposal_code" name="disposal_code" class="form-control" value="{{ $disposalCode }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="disposal_date">Ngày hủy:</label>
                                <input type="date" id="disposal_date" name="disposal_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="note">Ghi chú:</label>
                                <textarea id="note" name="note" class="form-control" rows="3" placeholder="Nhập ghi chú phiếu hủy..."></textarea>
                            </div>

                            <div class="summary-box">
                                <div class="summary-title">Tổng kết</div>
                                <div class="summary-item">
                                    <span class="summary-label">Tổng số sản phẩm:</span>
                                    <span class="summary-value" id="total-items">0</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Tổng số lượng:</span>
                                    <span class="summary-value" id="total-quantity">0</span>
                                </div>
                                <hr>
                                <div class="summary-item">
                                    <span class="summary-label">Tổng giá trị hủy:</span>
                                    <span class="summary-value total-amount" id="total-amount">0 VNĐ</span>
                                    <input type="hidden" name="total_amount" id="total_amount_input" value="0">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('disposal.index') }}" class="btn btn-back btn-action text-dark">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-save btn-action text-dark" id="saveButton" disabled>
                                    <i class="fas fa-save"></i> Lưu phiếu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Template for new row -->
    <template id="item-row-template">
        <tr class="item-row">
            <td class="text-center item-index"></td>
            <td>
                <span class="product-name"></span>
                <input type="hidden" name="product_ids[]" class="product-id">
            </td>
            <td>
                <select name="batch_ids[]" class="batch-select" required>
                    <option value="">Chọn lô</option>
                </select>
                <div class="batch-info"></div>
            </td>
            <td class="product-unit"></td>
            <td class="text-center stock-quantity"></td>
            <td>
                <input type="number" name="quantities[]" class="form-control input-quantity" min="1" required>
            </td>
            <td>
                <input type="number" name="prices[]" class="form-control input-price" min="0" required>
            </td>
            <td>
                <input type="text" name="reasons[]" class="form-control input-reason" required placeholder="Lý do hủy...">
            </td>
            <td class="text-right item-total">0 VNĐ</td>
            <td class="text-center">
                <button type="button" class="btn-remove remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>
    </template>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let itemCount = 0;
            let totalAmount = 0;

            // Tìm kiếm sản phẩm
            $('#search_product').on('input', function() {
                const searchTerm = $(this).val().trim();
                console.log('input', searchTerm)
                if (searchTerm.length < 2) {
                    $('.search-results').hide();
                    return;
                }

                // Gọi API tìm kiếm sản phẩm
                $.ajax({
                    url: "{{ route('api.products.search') }}",
                    method: 'GET',
                    data: { search: searchTerm, has_stock: 1 },
                    success: function(response) {
                        console.log('response', response)
                        renderSearchResults(response);
                    }
                });
            });

            // Hiển thị kết quả tìm kiếm
            function renderSearchResults(products) {
                const $results = $('.search-results');
                $results.empty();

                if (products.length === 0) {
                    $results.append('<div class="search-item">Không tìm thấy sản phẩm</div>');
                } else {
                    products.forEach(product => {
                        const $item = $(`
                        <div class="search-item" data-id="${product.id}">
                            <div class="product-name">${product.name}</div>
                            <div class="product-info">
                                Đơn vị: ${product.unit} | Tồn kho: ${product.total_stock}
                            </div>
                        </div>
                    `);
                        $results.append($item);
                    });
                }

                $results.show();
            }

            // Xử lý khi chọn sản phẩm từ kết quả tìm kiếm
            $(document).on('click', '.search-item', function() {
                const productId = $(this).data('id');

                // Kiểm tra sản phẩm đã được thêm chưa
                if ($(`input.product-id[value="${productId}"]`).length > 0) {
                    alert('Sản phẩm này đã được thêm vào phiếu!');
                    $('.search-results').hide();
                    $('#search_product').val('');
                    return;
                }

                // Lấy thông tin chi tiết sản phẩm và các lô
                $.ajax({
                    url: `{{ url('api/products') }}/${productId}/batches`,
                    method: 'GET',
                    success: function(response) {
                        addProductToTable(response.product, response.batches);
                        $('.search-results').hide();
                        $('#search_product').val('');
                    }
                });
            });

            // Thêm sản phẩm vào bảng
            function addProductToTable(product, batches) {
                // Kiểm tra nếu không có lô nào có hàng
                if (batches.length === 0) {
                    alert('Sản phẩm này không có lô nào còn hàng!');
                    return;
                }

                // Ẩn hàng trống
                $('#empty-row').hide();

                // Tăng số thứ tự
                itemCount++;

                // Lấy template và clone
                const template = document.getElementById('item-row-template').content.cloneNode(true);
                const row = template.querySelector('.item-row');

                // Cập nhật thông tin sản phẩm
                row.querySelector('.item-index').textContent = itemCount;
                row.querySelector('.product-name').textContent = product.name;
                row.querySelector('.product-id').value = product.id;
                row.querySelector('.product-unit').textContent = product.unit;

                // Thêm các lô vào dropdown
                const batchSelect = row.querySelector('.batch-select');
                batches.forEach(batch => {
                    const option = document.createElement('option');
                    option.value = batch.id;
                    option.textContent = `${batch.batch_number} - HSD: ${formatDate(batch.expiry_date)}`;
                    option.dataset.quantity = batch.quantity;
                    option.dataset.price = product.import_price;
                    option.dataset.manufacturing = batch.manufacturing_date;
                    option.dataset.expiry = batch.expiry_date;
                    batchSelect.appendChild(option);
                });

                // Thêm row vào bảng
                document.querySelector('#disposalItems tbody').appendChild(row);

                // Cập nhật số lượng tồn kho khi chọn lô
                $(row).find('.batch-select').on('change', function() {
                    const selectedOption = $(this).find('option:selected');
                    const stockQuantity = selectedOption.data('quantity');
                    const price = selectedOption.data('price');
                    const manufacturing = selectedOption.data('manufacturing');
                    const expiry = selectedOption.data('expiry');

                    $(row).find('.stock-quantity').text(stockQuantity);
                    $(row).find('.input-quantity').attr('max', stockQuantity);
                    $(row).find('.input-price').val(price);
                    // Hiển thị thông tin lô
                    $(row).find('.batch-info').html(`
                    NSX: ${formatDate(manufacturing)} - HSD: ${formatDate(expiry)}
                `);

                    // Reset số lượng
                    $(row).find('.input-quantity').val('');
                    updateItemTotal($(row));
                });

                // Cập nhật thành tiền khi thay đổi số lượng hoặc giá
                $(row).find('.input-quantity, .input-price').on('input', function() {
                    updateItemTotal($(row));
                });

                // Kích hoạt nút lưu
                updateSaveButtonState();
            }

            // Cập nhật thành tiền của một dòng
            function updateItemTotal(row) {
                const quantity = parseInt(row.find('.input-quantity').val()) || 0;
                const price = parseFloat(row.find('.input-price').val()) || 0;
                const total = quantity * price;

                row.find('.item-total').text(formatCurrency(total));

                updateTotals();
            }

            // Cập nhật tổng số tiền
            function updateTotals() {
                let totalItems = $('.item-row').length;
                let totalQuantity = 0;
                totalAmount = 0;

                $('.item-row').each(function() {
                    const quantity = parseInt($(this).find('.input-quantity').val()) || 0;
                    const price = parseFloat($(this).find('.input-price').val()) || 0;

                    totalQuantity += quantity;
                    totalAmount += quantity * price;
                });

                $('#total-items').text(totalItems);
                $('#total-quantity').text(totalQuantity);
                $('#total-amount').text(formatCurrency(totalAmount));
                $('#total_amount_input').val(totalAmount);

                updateSaveButtonState();
            }

            // Xóa sản phẩm khỏi bảng
            $(document).on('click', '.remove-item', function() {
                $(this).closest('tr').remove();

                // Cập nhật lại số thứ tự
                $('.item-row').each(function(index) {
                    $(this).find('.item-index').text(index + 1);
                });

                // Hiển thị lại hàng trống nếu không còn sản phẩm
                if ($('.item-row').length === 0) {
                    $('#empty-row').show();
                }

                updateTotals();
            });

            // Cập nhật trạng thái nút lưu
            function updateSaveButtonState() {
                const hasItems = $('.item-row').length > 0;
                let allFieldsFilled = true;

                // Kiểm tra xem tất cả các trường bắt buộc đã được điền chưa
                $('.item-row').each(function() {
                    const batchSelected = $(this).find('.batch-select').val() !== '';
                    const quantityFilled = $(this).find('.input-quantity').val() !== '';
                    const priceFilled = $(this).find('.input-price').val() !== '';
                    const reasonFilled = $(this).find('input[name="reasons[]"]').val() !== '';

                    if (!batchSelected || !quantityFilled || !priceFilled || !reasonFilled) {
                        allFieldsFilled = false;
                        return false;
                    }

                    // Kiểm tra số lượng hợp lệ
                    const quantity = parseInt($(this).find('.input-quantity').val());
                    const maxQuantity = parseInt($(this).find('.input-quantity').attr('max'));

                    if (quantity <= 0 || quantity > maxQuantity) {
                        allFieldsFilled = false;
                        return false;
                    }
                });

                $('#saveButton').prop('disabled', !(hasItems && allFieldsFilled));
            }

            // Kiểm tra trước khi submit form
            $('#disposalForm').on('submit', function(e) {
                let isValid = true;

                // Kiểm tra từng dòng sản phẩm
                $('.item-row').each(function() {
                    const batchSelect = $(this).find('.batch-select');
                    const quantityInput = $(this).find('.input-quantity');
                    const priceInput = $(this).find('.input-price');
                    const reasonInput = $(this).find('input[name="reasons[]"]');

                    // Kiểm tra lô đã chọn chưa
                    if (batchSelect.val() === '') {
                        batchSelect.addClass('is-invalid');
                        isValid = false;
                    } else {
                        batchSelect.removeClass('is-invalid');
                    }

                    // Kiểm tra số lượng
                    const quantity = parseInt(quantityInput.val());
                    const maxQuantity = parseInt(quantityInput.attr('max'));

                    if (isNaN(quantity) || quantity <= 0) {
                        quantityInput.addClass('is-invalid');
                        isValid = false;
                    } else if (quantity > maxQuantity) {
                        quantityInput.addClass('is-invalid');
                        alert(`Số lượng hủy không được vượt quá số lượng tồn kho (${maxQuantity})`);
                        isValid = false;
                    } else {
                        quantityInput.removeClass('is-invalid');
                    }

                    // Kiểm tra giá
                    const price = parseFloat(priceInput.val());
                    if (isNaN(price) || price < 0) {
                        priceInput.addClass('is-invalid');
                        isValid = false;
                    } else {
                        priceInput.removeClass('is-invalid');
                    }

                    // Kiểm tra lý do
                    if (reasonInput.val().trim() === '') {
                        reasonInput.addClass('is-invalid');
                        isValid = false;
                    } else {
                        reasonInput.removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Vui lòng kiểm tra lại thông tin phiếu hủy!');
                } else if (totalAmount === 0) {
                    e.preventDefault();
                    alert('Tổng giá trị hủy không thể bằng 0!');
                }
            });

            // Ẩn kết quả tìm kiếm khi click ra ngoài
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-product').length) {
                    $('.search-results').hide();
                }
            });

            // Hàm định dạng tiền tệ
            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND',
                    maximumFractionDigits: 0
                }).format(amount);
            }

            // Hàm định dạng ngày tháng
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('vi-VN');
            }

            // Kiểm tra trạng thái nút lưu khi có thay đổi trong form
            $(document).on('change input', 'input, select, textarea', function() {
                updateSaveButtonState();
            });
        });
    </script>
@endsection

