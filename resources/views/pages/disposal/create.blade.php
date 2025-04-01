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
                                        <th>Giá nhập</th>
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
                                <label for="invoice_code">Mã phiếu hủy:</label>
                                <input type="text" id="invoice_code" name="invoice_code" class="form-control" value="{{ $disposalCode }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="disposal_date">Ngày hủy:</label>
                                <input type="date" id="disposal_date" name="disposal_date" class="form-control disabled" value="{{ date('Y-m-d') }}" readonly>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            let itemCount = 0;
            let totalAmount = 0;
            let addedProducts = {}; // Object to track added products

            // Tìm kiếm sản phẩm
            $('#search_product').on('input', function() {
                const searchTerm = $(this).val().trim();
                // Nếu xóa hết text, hiển thị 10 sản phẩm mới nhất
                if (searchTerm.length === 0) {
                    loadRecentProducts();
                }

                // Gọi API tìm kiếm sản phẩm
                $.ajax({
                    url: "{{ route('api.products.search') }}",
                    method: 'GET',
                    data: { search: searchTerm, has_stock: 1 },
                    success: function(response) {
                        renderSearchResults(response);
                    }
                });
            });

            // Thêm sự kiện click vào input để hiển thị 10 sản phẩm mới nhất
            $('#search_product').on('click', function() {
                // Nếu input trống, hiển thị 10 sản phẩm mới nhất
                if ($(this).val().trim() === '') {
                    loadRecentProducts();
                }
            });

            // Hàm tải 10 sản phẩm mới nhất
            function loadRecentProducts() {
                $.ajax({
                    url: "{{ route('api.products.search') }}",
                    method: 'GET',
                    data: { recent: 1, limit: 10, has_stock: 1 },
                    success: function(response) {
                        renderSearchResults(response);
                    }
                });
            }

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
                                Đơn vị: ${product.unit} | Tồn kho: ${product.quantity}
                            </div>
                        </div>
                    `);
                        $results.append($item);
                    });
                }

                $results.show();
            }

            // Xử lý khi chọn sản phẩm từ kết quả tìm kiếm
            $(document).on('click', '.search-item', function () {
                const productId = $(this).data('id');

                // Lấy thông tin chi tiết sản phẩm và các lô
                $.ajax({
                    url: `{{ url('api/products') }}/${productId}/batches`,
                    method: 'GET',
                    success: function (response) {
                        // Lưu thông tin sản phẩm để sử dụng sau này
                        addedProducts[productId] = response.product;

                        // Hiển thị modal chọn lô hoặc thêm trực tiếp lô đầu tiên
                        showBatchSelectionModal(response.product, response.batches);

                        $('.search-results').hide();
                        $('#search_product').val('');
                    }
                });
            });

            // Hiển thị modal chọn lô
            function showBatchSelectionModal(product, batches) {
                // Kiểm tra nếu không có lô nào có hàng
                if (batches.length === 0) {
                    alert('Sản phẩm này không có lô nào còn hàng!');
                    return;
                }

                // Tạo modal để chọn lô
                let modalHtml = `
                <div class="modal fade" id="batchSelectionModal" tabindex="-1" role="dialog" aria-labelledby="batchSelectionModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="batchSelectionModalLabel">Chọn lô cho sản phẩm: ${product.name}</h5>
                                <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mã lô</th>
                                                <th>NSX</th>
                                                <th>HSD</th>
                                                <th>SL tồn</th>
                                                <th>Giá nhập</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                batches.forEach(batch => {
                    // Kiểm tra xem lô đã được thêm vào bảng chưa
                    const batchAlreadyAdded = $(`.batch-id[value="${batch.id}"]`).length > 0;

                    modalHtml += `
                        <tr>
                            <td>${batch.batch_number}</td>
                            <td>${formatDate(batch.manufacturing_date)}</td>
                            <td>${formatDate(batch.expiry_date)}</td>
                            <td>${batch.quantity}</td>
                            <td>${formatCurrency(batch.import_price)}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary select-batch"
                                    data-id="${batch.id}"
                                    data-product-id="${product.id}"
                                    data-batch-number="${batch.batch_number}"
                                    data-quantity="${batch.quantity}"
                                    data-price="${batch.import_price}"
                                    data-manufacturing="${batch.manufacturing_date}"
                                    data-expiry="${batch.expiry_date}"
                                    ${batchAlreadyAdded ? 'disabled' : ''}>
                                    ${batchAlreadyAdded ? 'Đã thêm' : 'Thêm lô này'}
                                </button>
                            </td>
                        </tr>
                    `;
                });

                modalHtml += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                // Thêm modal vào body và hiển thị
                if ($('#batchSelectionModal').length) {
                    $('#batchSelectionModal').remove();
                }

                $('body').append(modalHtml);
                $('#batchSelectionModal').modal('show');

                // Xử lý sự kiện đóng modal
                $('.close, .close-modal').on('click', function() {
                    $('#batchSelectionModal').modal('hide');
                    setTimeout(function() {
                        $('#batchSelectionModal').remove();
                    }, 500);
                });

                // Hiển thị modal
                $('#batchSelectionModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }

            // Thêm xử lý khi modal được ẩn đi
            $(document).on('hidden.bs.modal', '#batchSelectionModal', function() {
                $(this).remove();
            });

            // Xử lý khi chọn lô từ modal
            $(document).on('click', '.select-batch', function () {
                const batchId = $(this).data('id');
                const productId = $(this).data('product-id');
                const batchNumber = $(this).data('batch-number');
                const quantity = $(this).data('quantity');
                const price = $(this).data('price');
                const manufacturing = $(this).data('manufacturing');
                const expiry = $(this).data('expiry');

                // Thêm lô vào bảng
                addBatchToTable(productId, batchId, batchNumber, quantity, price, manufacturing, expiry);

                // Disable nút sau khi đã chọn
                $(this).prop('disabled', true).text('Đã thêm');
            });

            // Thêm lô vào bảng
            function addBatchToTable(productId, batchId, batchNumber, stockQuantity, price, manufacturing, expiry) {
                // Ẩn hàng trống
                $('#empty-row').hide();

                // Tăng số thứ tự
                itemCount++;

                // Lấy thông tin sản phẩm
                const product = addedProducts[productId];

                // Tạo row mới
                const newRow = $(`
                    <tr class="item-row">
                        <td class="text-center item-index">${itemCount}</td>
                        <td>
                            <span class="product-name">${product.name}</span>
                            <input type="hidden" name="items[${itemCount - 1}][product_id]" class="product-id" value="${productId}">
                            <input type="hidden" name="items[${itemCount - 1}][batch_id]" class="batch-id" value="${batchId}">
                        </td>
                        <td>
                            <div>${batchNumber}</div>
                            <div class="batch-info">NSX: ${formatDate(manufacturing)} - HSD: ${formatDate(expiry)}</div>
                        </td>
                        <td class="product-unit">${product.unit}</td>
                        <td class="text-center stock-quantity">${stockQuantity}</td>
                        <td>
                            <input type="number" name="items[${itemCount - 1}][quantity]" class="form-control input-quantity" min="1" max="${stockQuantity}" required>
                        </td>
                        <td>
                            <input type="number" name="items[${itemCount - 1}][price]" class="form-control input-price" min="0" value="${price}" readonly>
                        </td>
                        <td>
                            <input type="text" name="items[${itemCount - 1}][reason]" class="form-control input-reason" required placeholder="Lý do hủy...">
                        </td>
                        <td class="text-right item-total">0 VNĐ</td>
                        <td class="text-center">
                            <button type="button" class="btn-remove remove-item" data-batch-id="${batchId}">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                `);

                // Thêm row vào bảng
                $('#disposalItems tbody').append(newRow);

                // Cập nhật thành tiền khi thay đổi số lượng
                newRow.find('.input-quantity').on('input', function () {
                    updateItemTotal(newRow);
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

                $('.item-row').each(function () {
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
            $(document).on('click', '.remove-item', function () {
                const batchId = $(this).data('batch-id');
                $(this).closest('tr').remove();

                // Cập nhật lại số thứ tự và name attributes
                $('.item-row').each(function (index) {
                    $(this).find('.item-index').text(index + 1);
                    $(this).find('input[name^="items["]').each(function () {
                        const name = $(this).attr('name');
                        const newName = name.replace(/items\[\d+\]/, `items[${index}]`);
                        $(this).attr('name', newName);
                    });
                });

                // Hiển thị lại hàng trống nếu không còn sản phẩm
                if ($('.item-row').length === 0) {
                    $('#empty-row').show();
                }

                // Nếu modal đang mở, cập nhật trạng thái nút cho lô đã xóa
                if ($('#batchSelectionModal').is(':visible')) {
                    $(`.select-batch[data-id="${batchId}"]`).prop('disabled', false).text('Thêm lô này');
                }

                updateTotals();
            });

            // Cập nhật trạng thái nút lưu
            function updateSaveButtonState() {
                const hasItems = $('.item-row').length > 0;
                let allFieldsFilled = true;

                // Kiểm tra xem tất cả các trường bắt buộc đã được điền chưa
                $('.item-row').each(function () {
                    const quantityFilled = $(this).find('.input-quantity').val() !== '';
                    const reasonFilled = $(this).find('.input-reason').val() !== '';

                    if (!quantityFilled || !reasonFilled) {
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
            $('#disposalForm').on('submit', function (e) {
                let isValid = true;

                // Kiểm tra từng dòng sản phẩm
                $('.item-row').each(function () {
                    const quantityInput = $(this).find('.input-quantity');
                    const reasonInput = $(this).find('.input-reason');

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

            // Thêm nút để mở lại modal chọn lô cho sản phẩm đã thêm
            $(document).on('click', '.add-more-batch', function () {
                const productId = $(this).data('product-id');

                // Lấy thông tin chi tiết sản phẩm và các lô
                $.ajax({
                    url: `{{ url('api/products') }}/${productId}/batches`,
                    method: 'GET',
                    success: function (response) {
                        showBatchSelectionModal(response.product, response.batches);
                    }
                });
            });

            // Ẩn kết quả tìm kiếm khi click ra ngoài
            $(document).on('click', function (e) {
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
            $(document).on('change input', 'input, select, textarea', function () {
                updateSaveButtonState();
            });

            // Add this function inside the document.ready block
            function updateAddedProductsList() {
                const $container = $('#added-products-container');
                $container.empty();

                // Group by product ID
                const productGroups = {};
                $('.item-row').each(function() {
                    const productId = $(this).find('.product-id').val();
                    const productName = $(this).find('.product-name').text();

                    if (!productGroups[productId]) {
                        productGroups[productId] = {
                            name: productName,
                            count: 0
                        };
                    }

                    productGroups[productId].count++;
                });

                // Create badges for each product
                for (const [productId, info] of Object.entries(productGroups)) {
                    const $badge = $(`
            <div class="badge badge-info mr-2 mb-2 p-2" style="font-size: 14px;">
                ${info.name} (${info.count} lô)
                <button type="button" class="btn btn-sm btn-light ml-2 add-more-batch" data-product-id="${productId}">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        `);

                    $container.append($badge);
                }
            }

        // Call this function after adding or removing items
        // Add this line to the end of addBatchToTable function:
        updateAddedProductsList();

        // And also add it to the remove-item click handler after updating totals:
        updateAddedProductsList();
        });
    </script>
@endsection

