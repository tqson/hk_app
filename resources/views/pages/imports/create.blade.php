@extends('layouts.app')

@section('styles')
    <style>
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

        .batch-info {
            font-size: 12px;
            color: rgba(0, 0, 0, 0.45);
            margin-top: 4px;
        }

        #added-products-container {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        #added-products-container .badge {
            font-size: 14px;
            padding: 8px 12px;
            margin-right: 8px;
            margin-bottom: 8px;
            background-color: #e6f7ff;
            color: #1890ff;
            border: 1px solid #91d5ff;
            border-radius: 4px;
        }

        #added-products-container .btn-sm {
            padding: 0px 5px;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tạo hóa đơn nhập hàng</h3>
                        <div class="card-tools">
                            <a href="{{ route('imports.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="importForm" action="{{ route('imports.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Tìm kiếm sản phẩm -->
                                    <div class="form-group search-product">
                                        <label for="product_search">Tìm kiếm sản phẩm:</label>
                                        <input type="text" id="product_search" class="form-control" placeholder="Nhập tên sản phẩm...">
                                        <div class="search-results" style="display: none;"></div>
                                    </div>

                                    <div id="added-products-container" class="mb-3"></div>
                                    <!-- Danh sách sản phẩm tìm thấy -->
                                    <div id="product_search_results" class="mb-3" style="display: none;">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                <tr>
                                                    <th>Mã SP</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Đơn vị</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                                </thead>
                                                <tbody id="product_search_body">
                                                <!-- Kết quả tìm kiếm sẽ được hiển thị ở đây -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Bảng sản phẩm đã chọn -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="selected_products_table">
                                            <thead>
                                            <tr>
                                                <th style="width: 50px">STT</th>
                                                <th style="width: 80px">Xóa</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Lô - NSX - HSD</th>
                                                <th>Đơn vị</th>
                                                <th>Số lượng</th>
                                                <th>Giá nhập</th>
                                                <th>Tổng tiền</th>
                                            </tr>
                                            </thead>
                                            <tbody id="selected_products_body">
                                            <!-- Sản phẩm đã chọn sẽ được hiển thị ở đây -->
                                            <tr id="no_products_row">
                                                <td colspan="8" class="text-center">Chưa có sản phẩm nào được chọn</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Thông tin phiếu nhập -->
                                    <div class="card">
                                        <div class="card-header bg-primary">
                                            <h3 class="card-title">Thông tin phiếu nhập</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="expected_date mb-2">Ngày nhận hàng dự kiến:</label>
                                                <input type="date" class="form-control w-100" id="expected_date"
                                                       name="expected_date" required value="{{ date('Y-m-d') }}">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="supplier_id">Nhà cung cấp:</label>
                                                <select class="form-control select2" id="supplier_id" name="supplier_id"
                                                        required>
                                                    <option value="">-- Chọn nhà cung cấp --</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option
                                                            value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Tổng tiền:</label>
                                                <input type="text" class="form-control" id="total_amount"
                                                       name="total_amount" readonly value="0">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="vat">VAT (%):</label>
                                                <input type="number" class="form-control" id="vat_percent" min="0"
                                                       max="100" value="10" step="0.1">
                                                <input type="hidden" id="vat" name="vat" value="0">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="discount_percent">Giảm giá (%):</label>
                                                <input type="number" class="form-control" id="discount_percent"
                                                       name="discount_percent" min="0" max="100" value="0" step="0.1">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Tổng cần trả:</label>
                                                <input type="text" class="form-control" id="final_amount"
                                                       name="final_amount" readonly value="0">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="paid_amount">Đã trả NCC:</label>
                                                <input type="number" class="form-control" id="paid_amount"
                                                       name="paid_amount" min="0" value="0">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Nợ NCC:</label>
                                                <input type="text" class="form-control" id="debt_amount"
                                                       name="debt_amount" readonly value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('imports.index') }}" class="btn btn-secondary">Hủy</a>
                                    <button type="submit" class="btn btn-primary" id="saveImportBtn">Lưu hóa đơn
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm lô sản phẩm -->
    <div class="modal fade" id="batchModal" tabindex="-1" role="dialog" aria-labelledby="batchModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batchModalLabel">Thêm lô sản phẩm mới</h5>
                    <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="batchForm">
                    <div class="modal-body">
                        <input type="hidden" id="batch_product_id" name="product_id">
                        <input type="hidden" id="batch_row_index" name="row_index">

                        <div class="form-group">
                            <label for="batch_number">Số lô:</label>
                            <input type="text" class="form-control" id="batch_number" name="batch_number" required>
                        </div>

                        <div class="form-group">
                            <label for="manufacturing_date">Ngày sản xuất:</label>
                            <input type="date" class="form-control" id="manufacturing_date" name="manufacturing_date" required>
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">Hạn sử dụng:</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                        </div>

                        <div class="form-group">
                            <label for="batch_import_price">Giá nhập:</label>
                            <input type="number" class="form-control" id="batch_import_price" name="import_price" min="1000" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo lô</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal chọn lô sản phẩm -->
    <div class="modal fade" id="batchSelectionModal" tabindex="-1" role="dialog" aria-labelledby="batchSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batchSelectionModalLabel">Chọn lô cho sản phẩm: <span id="modal-product-name"></span></h5>
                    <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Chọn</th>
                                <th>Mã lô</th>
                                <th>NSX</th>
                                <th>HSD</th>
                                <th>Số lượng</th>
                                <th>Giá nhập</th>
                            </tr>
                            </thead>
                            <tbody id="batches-table-body">
                            <!-- Danh sách lô sẽ được thêm vào đây -->
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary" id="add-new-batch-btn">
                            <i class="fas fa-plus"></i> Thêm lô mới
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="add-selected-batches">Thêm các lô đã chọn</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Khởi tạo Select2
            $('.select2').select2();

            // Biến lưu trữ sản phẩm đã chọn
            let selectedProducts = [];
            let addedProducts = {}; // Object để theo dõi sản phẩm đã thêm

            //  PHẦN TÌM KIẾM SẢN PHẨM
            function setupProductSearch() {
                // Tìm kiếm sản phẩm khi nhập
                $('#product_search').on('input', function() {
                    searchProducts($(this).val().trim());
                });

                // Hiển thị sản phẩm gần đây khi click vào ô tìm kiếm
                $('#product_search').on('click', function() {
                    if ($(this).val().trim() === '') {
                        loadRecentProducts();
                    }
                });

                // Ẩn kết quả tìm kiếm khi click ra ngoài
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.search-product').length) {
                        $('.search-results').hide();
                    }
                });

                // Xử lý khi chọn sản phẩm từ kết quả tìm kiếm
                $(document).on('click', '.search-item', handleProductSelect);
            }

            // Tìm kiếm sản phẩm
            function searchProducts(searchTerm) {
                if (searchTerm.length === 0) {
                    loadRecentProducts();
                } else {
                    // Gọi API tìm kiếm sản phẩm
                    $.ajax({
                        url: "/api/products/search",
                        method: 'GET',
                        data: { search: searchTerm },
                        success: renderSearchResults,
                        error: function(error) {
                            console.error('Error searching products:', error);
                        }
                    });
                }
            }

            // Hàm tải sản phẩm gần đây
            function loadRecentProducts() {
                $.ajax({
                    url: "/api/products/search",
                    method: 'GET',
                    data: { recent: 1, limit: 10 },
                    success: renderSearchResults,
                    error: function(error) {
                        console.error('Error loading recent products:', error);
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
                            Đơn vị: ${product.unit} | Mã SP: ${product.id}
                        </div>
                    </div>
                `);
                        $results.append($item);
                    });
                }

                $results.show();
            }

            // Xử lý khi chọn sản phẩm từ kết quả tìm kiếm
            function handleProductSelect() {
                const productId = $(this).data('id');

                // Lấy thông tin chi tiết sản phẩm và các lô
                $.ajax({
                    url: `/product-batches/by-product/${productId}`,
                    method: 'GET',
                    success: function(response) {
                        // Lưu thông tin sản phẩm
                        const product = {
                            id: productId,
                            name: $(`.search-item[data-id="${productId}"] .product-name`).text(),
                            unit: $(`.search-item[data-id="${productId}"] .product-info`).text().split('|')[0].replace('Đơn vị:', '').trim()
                        };

                        addedProducts[productId] = product;

                        // Hiển thị modal chọn lô
                        showBatchSelectionModal(product, response);

                        // Ẩn kết quả tìm kiếm và xóa text
                        $('.search-results').hide();
                        $('#product_search').val('');
                    },
                    error: function(error) {
                        console.error('Error loading product batches:', error);
                        alert('Đã xảy ra lỗi khi tải thông tin lô sản phẩm');
                    }
                });
            }

            //  PHẦN XỬ LÝ LÔ SẢN PHẨM
            function setupBatchHandling() {
                // Xử lý nút thêm lô mới trong modal
                $('#add-new-batch-btn').click(handleAddNewBatch);

                // Xử lý nút thêm các lô đã chọn
                $('#add-selected-batches').click(handleAddSelectedBatches);

                // Xử lý nút thêm lô cho sản phẩm đã thêm
                $(document).on('click', '.add-more-batch', handleAddMoreBatch);

                // Xử lý form thêm lô mới
                $('#batchForm').submit(handleBatchFormSubmit);

                // Thiết lập đóng modal
                setupModalClosing(['#batchSelectionModal', '#batchModal']);
            }

            // Hiển thị modal chọn lô
            function showBatchSelectionModal(product, batches) {
                // Cập nhật tên sản phẩm trong modal
                $('#modal-product-name').text(product.name);

                // Cập nhật danh sách lô
                const $batchesTableBody = $('#batches-table-body');
                $batchesTableBody.empty();

                if (batches.length === 0) {
                    $batchesTableBody.append(`
                <tr>
                    <td colspan="6" class="text-center">Không có lô nào cho sản phẩm này</td>
                </tr>
            `);
                } else {
                    batches.forEach(batch => {
                        // Kiểm tra xem lô đã được thêm vào danh sách chưa
                        const batchAlreadyAdded = selectedProducts.some(p =>
                            p.id === product.id && p.batch_id === batch.id
                        );

                        const mfgDate = new Date(batch.manufacturing_date).toLocaleDateString('vi-VN');
                        const expDate = new Date(batch.expiry_date).toLocaleDateString('vi-VN');

                        // Xác định xem đây là lô cũ hay không (có số lượng > 0)
                        const isExistingBatch = batch.quantity > 0;

                        $batchesTableBody.append(`
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="batch-checkbox"
                                data-batch-id="${batch.id}"
                                data-batch-number="${batch.batch_number}"
                                data-mfg-date="${batch.manufacturing_date}"
                                data-exp-date="${batch.expiry_date}"
                                data-import-price="${batch.import_price || 0}"
                                data-is-existing="${isExistingBatch}"
                                ${batchAlreadyAdded ? 'disabled checked' : ''}>
                        </td>
                        <td>${batch.batch_number}</td>
                        <td>${mfgDate}</td>
                        <td>${expDate}</td>
                        <td>
                            <input type="number" class="form-control batch-quantity" min="1" value="1"
                                ${batchAlreadyAdded ? 'disabled' : ''}>
                        </td>
                        <td>
                            <input type="number" class="form-control batch-price" min="0"
                                value="${batch.import_price || 0}"
                                ${batchAlreadyAdded || isExistingBatch ? 'disabled' : ''}>
                        </td>
                    </tr>
                `);
                    });
                }

                // Lưu ID sản phẩm hiện tại vào modal
                $('#batchSelectionModal').data('product-id', product.id);

                // Hiển thị modal
                $('#batchSelectionModal').modal('show');
            }

            // Thiết lập đóng modal
            function setupModalClosing(modalIds) {
                modalIds.forEach(modalId => {
                    // Đảm bảo các nút đóng hoạt động
                    $(`${modalId} .close, ${modalId} button[data-dismiss="modal"]`).on('click', function() {
                        $(modalId).modal('hide');
                    });

                    // Đóng modal khi click bên ngoài
                    $(modalId).on('click', function(e) {
                        if ($(e.target).is(modalId)) {
                            $(modalId).modal('hide');
                        }
                    });
                });
            }

            // Xử lý nút thêm lô mới trong modal
            function handleAddNewBatch() {
                // Lưu thông tin sản phẩm hiện tại
                const productId = $('#batchSelectionModal').data('product-id');

                // Đặt giá trị cho modal thêm lô
                $('#batch_product_id').val(productId);
                $('#batch_row_index').val(''); // Không cần row index vì đang thêm lô mới

                // Đóng modal chọn lô
                $('#batchSelectionModal').modal('hide');

                // Mở modal thêm lô mới
                $('#batchModal').modal('show');

                // Xử lý sau khi thêm lô mới xong
                $('#batchModal').on('hidden.bs.modal', function() {
                    // Mở lại modal chọn lô và tải lại danh sách
                    $.ajax({
                        url: `/product-batches/by-product/${productId}`,
                        method: 'GET',
                        success: function(response) {
                            showBatchSelectionModal(addedProducts[productId], response);
                        }
                    });
                });
            }

            // Xử lý nút thêm các lô đã chọn
            function handleAddSelectedBatches() {
                const productId = $('#batchSelectionModal').data('product-id');
                const product = addedProducts[productId];
                let batchesAdded = false;
                let hasError = false;

                // Lặp qua tất cả các checkbox đã chọn
                $('.batch-checkbox:checked:not(:disabled)').each(function() {
                    const $row = $(this).closest('tr');
                    const batchId = $(this).data('batch-id');
                    const batchNumber = $(this).data('batch-number');
                    const mfgDate = $(this).data('mfg-date');
                    const expDate = $(this).data('exp-date');
                    const quantity = parseInt($row.find('.batch-quantity').val()) || 1;
                    const price = parseFloat($row.find('.batch-price').val()) || 0;

                    // Kiểm tra giá nhập cho lô mới
                    const isExistingBatch = $(this).data('is-existing') === true;
                    if (!isExistingBatch && price <= 0) {
                        $row.find('.batch-price').addClass('is-invalid');
                        if (!hasError) {
                            alert('Giá nhập cho lô mới phải lớn hơn 0');
                            hasError = true;
                        }
                        return true; // continue
                    }

                    // Thêm sản phẩm với lô đã chọn vào danh sách
                    selectedProducts.push({
                        id: productId,
                        name: product.name,
                        unit: product.unit,
                        batch_id: batchId,
                        batch_number: batchNumber,
                        manufacturing_date: mfgDate,
                        expiry_date: expDate,
                        quantity: quantity,
                        import_price: price,
                        total_price: quantity * price,
                        is_existing_batch: isExistingBatch
                    });

                    batchesAdded = true;
                });

                if (hasError) {
                    return; // Dừng xử lý nếu có lỗi
                }

                if (batchesAdded) {
                    // Cập nhật giao diện
                    updateSelectedProductsTable();
                    updateAddedProductsList();

                    // Đóng modal
                    $('#batchSelectionModal').modal('hide');
                } else {
                    alert('Vui lòng chọn ít nhất một lô sản phẩm');
                }
            }

            // Xử lý nút thêm lô cho sản phẩm đã thêm
            function handleAddMoreBatch() {
                const productId = $(this).data('product-id');
                const product = addedProducts[productId];

                // Lấy danh sách lô cho sản phẩm
                $.ajax({
                    url: `/product-batches/by-product/${productId}`,
                    method: 'GET',
                    success: function(response) {
                        showBatchSelectionModal(product, response);
                    },
                    error: function(error) {
                        console.error('Error loading product batches:', error);
                        alert('Đã xảy ra lỗi khi tải thông tin lô sản phẩm');
                    }
                });
            }

            // Xử lý form thêm lô mới
            function handleBatchFormSubmit(e) {
                e.preventDefault();

                const productId = $('#batch_product_id').val();
                const batchNumber = $('#batch_number').val();
                const manufacturingDate = $('#manufacturing_date').val();
                const expiryDate = $('#expiry_date').val();

                // Kiểm tra tính hợp lệ của ngày tháng
                if (!validateDates(manufacturingDate, expiryDate)) {
                    return;
                }

                const importPrice = parseFloat($('#batch_import_price').val()) || 0;
                if (importPrice <= 0) {
                    alert('Giá nhập cho lô mới phải lớn hơn 0');
                    $('#batch_import_price').focus();
                    return;
                }

                $.ajax({
                    url: "/product-batches",
                    method: 'POST',
                    data: {
                        _token: '{{ @csrf_token() }}',
                        product_id: productId,
                        batch_number: batchNumber,
                        manufacturing_date: manufacturingDate,
                        expiry_date: expiryDate,
                        import_price: importPrice
                    },
                    success: function(response) {
                        if (response.success) {
                            // Thông báo
                            toastr.success('Thêm lô sản phẩm thành công!');

                            // Reset form
                            $('#batchForm')[0].reset();

                            // Đóng modal
                            $('#batchModal').modal('hide');
                        } else {
                            toastr.error('Đã xảy ra lỗi: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Đã xảy ra lỗi khi thêm lô sản phẩm';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        }
                        alert(errorMessage);
                    }
                });
            }

            // Kiểm tra tính hợp lệ của ngày tháng
            function validateDates(manufacturingDate, expiryDate) {
                // Kiểm tra ngày sản xuất phải trong quá khứ hoặc hiện tại
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const mfgDate = new Date(manufacturingDate);
                mfgDate.setHours(0, 0, 0, 0);

                if (mfgDate > today) {
                    alert('Ngày sản xuất không thể nằm trong tương lai');
                    return false;
                }

                // Kiểm tra ngày hết hạn phải trong tương lai
                const expDate = new Date(expiryDate);
                expDate.setHours(0, 0, 0, 0);

                if (expDate <= today) {
                    alert('Hạn sử dụng phải nằm trong tương lai');
                    return false;
                }

                if (expDate <= mfgDate) {
                    alert('Hạn sử dụng phải sau ngày sản xuất');
                    return false;
                }

                return true;
            }

            //  PHẦN XỬ LÝ SẢN PHẨM ĐÃ CHỌN
            function setupProductHandling() {
                // Xóa sản phẩm khỏi danh sách đã chọn
                $(document).on('click', '.remove-product', handleRemoveProduct);

                // Cập nhật số lượng sản phẩm
                $(document).on('input', '.product-quantity', handleQuantityChange);

                // Thêm validate khi người dùng nhập giá
                $(document).on('input', '.batch-price', validateBatchPrice);
            }

            // Xóa sản phẩm khỏi danh sách đã chọn
            function handleRemoveProduct() {
                const index = $(this).data('index');

                // Xóa sản phẩm khỏi mảng
                selectedProducts.splice(index, 1);

                // Cập nhật giao diện
                updateSelectedProductsTable();
                updateAddedProductsList();
            }

            // Cập nhật số lượng sản phẩm
            function handleQuantityChange() {
                const index = $(this).data('index');
                const quantity = parseInt($(this).val()) || 0;

                if (quantity < 1) {
                    $(this).val(1);
                    selectedProducts[index].quantity = 1;
                } else {
                    selectedProducts[index].quantity = quantity;
                }

                // Cập nhật tổng tiền sản phẩm
                updateProductTotal(index);
            }

            // Validate giá nhập lô
            function validateBatchPrice() {
                const price = parseFloat($(this).val()) || 0;
                const $row = $(this).closest('tr');
                const isExistingBatch = $row.find('.batch-checkbox').data('is-existing') === true;

                if (!isExistingBatch && price <= 0) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            }

            // Cập nhật bảng sản phẩm đã chọn
            function updateSelectedProductsTable() {
                const tbody = $('#selected_products_body');
                tbody.empty();

                if (selectedProducts.length === 0) {
                    tbody.append('<tr id="no_products_row"><td colspan="8" class="text-center">Chưa có sản phẩm nào được chọn</td></tr>');
                } else {
                    selectedProducts.forEach((product, index) => {
                        const mfgDate = new Date(product.manufacturing_date).toLocaleDateString('vi-VN');
                        const expDate = new Date(product.expiry_date).toLocaleDateString('vi-VN');
                        const batchInfo = `${product.batch_number} - NSX: ${mfgDate} - HSD: ${expDate}`;

                        tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-product" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <td>
                            ${product.name}
                            <input type="hidden" name="products[${index}][product_id]" value="${product.id}">
                        </td>
                        <td>
                            ${batchInfo}
                            <input type="hidden" name="products[${index}][product_batch_id]" value="${product.batch_id}">
                        </td>
                        <td>${product.unit}</td>
                        <td>
                            <input type="number" class="form-control product-quantity" data-index="${index}"
                                name="products[${index}][quantity]" min="1" value="${product.quantity}">
                        </td>
                        <td>
                            <input type="text" class="form-control product-price" data-index="${index}"
                                    name="products[${index}][import_price]" min="0" value="${product.import_price.toLocaleString('vi-VN')} VNĐ" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control product-total" data-index="${index}"
                                name="products[${index}][total_price]" readonly value="${product.total_price.toLocaleString('vi-VN')} VNĐ">
                        </td>
                    </tr>
                `);
                    });
                }

                // Cập nhật tổng tiền
                calculateTotals();
            }

            // Cập nhật danh sách sản phẩm đã thêm
            function updateAddedProductsList() {
                const $container = $('#added-products-container');
                $container.empty();

                // Nhóm theo ID sản phẩm
                const productGroups = {};
                selectedProducts.forEach(product => {
                    if (!productGroups[product.id]) {
                        productGroups[product.id] = {
                            name: product.name,
                            count: 0
                        };
                    }
                    productGroups[product.id].count++;
                });

                // Tạo badge cho mỗi sản phẩm
                for (const [productId, info] of Object.entries(productGroups)) {
                    const $badge = $(`
                <div class="badge mr-2 mb-2">
                    ${info.name} (${info.count} lô)
                    <button type="button" class="btn btn-sm btn-light ml-2 add-more-batch" data-product-id="${productId}">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            `);

                    $container.append($badge);
                }
            }

            // Cập nhật tổng tiền của một sản phẩm
            function updateProductTotal(index) {
                const product = selectedProducts[index];
                const total = product.quantity * product.import_price;

                product.total_price = total;
                $(`.product-total[data-index="${index}"]`).val(total.toLocaleString('vi-VN') + ' VNĐ');

                // Cập nhật tổng tiền hóa đơn
                calculateTotals();
            }

            //  PHẦN XỬ LÝ TÍNH TOÁN TỔNG TIỀN
            function setupTotalCalculation() {
                // Cập nhật tổng tiền khi thay đổi VAT
                $('#vat_percent').on('input', calculateTotals);

                // Cập nhật tổng tiền khi thay đổi giảm giá
                $('#discount_percent').on('input', calculateTotals);

                // Cập nhật nợ khi thay đổi số tiền đã trả
                $('#paid_amount').on('input', handlePaidAmountChange);
            }

            // Xử lý khi thay đổi số tiền đã trả
            function handlePaidAmountChange() {
                const paidAmount = parseFloat($(this).val()) || 0;
                const finalAmount = parseFloat($('#final_amount').val().replace(/\./g, '').replace(',', '.')) || 0;

                if (paidAmount > finalAmount) {
                    $(this).val(finalAmount);
                    alert('Số tiền đã trả không được vượt quá tổng cần trả');
                }

                calculateTotals();
            }

            // Tính toán tổng tiền hóa đơn
            function calculateTotals() {
                // Tổng tiền sản phẩm
                let totalAmount = 0;
                selectedProducts.forEach(product => {
                    totalAmount += product.total_price;
                });

                // VAT
                const vatPercent = parseFloat($('#vat_percent').val()) || 0;
                const vatAmount = totalAmount * (vatPercent / 100);

                // Giảm giá
                const discountPercent = parseFloat($('#discount_percent').val()) || 0;
                const discountAmount = totalAmount * (discountPercent / 100);

                // Tổng cần trả
                const finalAmount = totalAmount + vatAmount - discountAmount;

                // Đã trả
                const paidAmount = parseFloat($('#paid_amount').val()) || 0;

                // Nợ
                const debtAmount = finalAmount - paidAmount;

                // Cập nhật giao diện
                $('#total_amount').val(totalAmount.toLocaleString('vi-VN'));
                $('#vat').val(vatAmount);
                $('#final_amount').val(finalAmount.toLocaleString('vi-VN'));
                $('#debt_amount').val(debtAmount.toLocaleString('vi-VN'));
            }

            //  PHẦN XỬ LÝ FORM SUBMIT
            function setupFormSubmit() {
                // Xử lý submit form
                $('#importForm').submit(handleFormSubmit);
            }

            // Xử lý submit form
            function handleFormSubmit(e) {
                if (selectedProducts.length === 0) {
                    e.preventDefault();
                    alert('Vui lòng chọn ít nhất một sản phẩm');
                    return;
                }

                // Cập nhật giá trị thực tế cho các input hidden
                $('#total_amount').val(parseFloat($('#total_amount').val().replace(/\./g, '').replace(',', '.')));
                $('#final_amount').val(parseFloat($('#final_amount').val().replace(/\./g, '').replace(',', '.')));
                $('#debt_amount').val(parseFloat($('#debt_amount').val().replace(/\./g, '').replace(',', '.')));

                // Cập nhật tổng tiền cho mỗi sản phẩm
                selectedProducts.forEach((product, index) => {
                    $(`input[name="products[${index}][import_price]"]`).val(product.import_price);
                    $(`input[name="products[${index}][total_price]"]`).val(product.total_price);
                });
            }

            //  PHẦN THIẾT LẬP NGÀY THÁNG
            function setupDateConstraints() {
                // Lấy ngày hiện tại ở định dạng YYYY-MM-DD
                const today = new Date().toISOString().split('T')[0];

                // Thiết lập ngày sản xuất tối đa là ngày hiện tại
                $('#manufacturing_date').attr('max', today);

                // Thiết lập ngày hết hạn tối thiểu là ngày mai
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
                $('#expiry_date').attr('min', tomorrowFormatted);
            }

            //  KHỞI TẠO CÁC CHỨC NĂNG
            function initializeApp() {
                setupProductSearch();
                setupBatchHandling();
                setupProductHandling();
                setupTotalCalculation();
                setupFormSubmit();
                setupDateConstraints();

                // Thiết lập ngày tháng khi mở modal
                $('#batchModal').on('shown.bs.modal', setupDateConstraints);
            }

            // Khởi chạy ứng dụng
            initializeApp();
        });
    </script>
@endsection
