@extends('layouts.app')

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
                                    <div class="form-group">
                                        <label>Tìm kiếm sản phẩm:</label>
                                        <div class="input-group">
                                            <input type="text" id="product_search" class="form-control"
                                                   placeholder="Nhập tên sản phẩm...">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-primary" id="search_product_btn">
                                                    <i class="fas fa-search"></i> Tìm kiếm
                                                </button>
                                            </div>
                                        </div>
                                    </div>

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
                                            <div class="form-group">
                                                <label for="expected_date">Ngày nhận hàng dự kiến:</label>
                                                <input type="date" class="form-control" id="expected_date"
                                                       name="expected_date" required value="{{ date('Y-m-d') }}">
                                            </div>

                                            <div class="form-group">
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

                                            <div class="form-group">
                                                <label>Tổng tiền:</label>
                                                <input type="text" class="form-control" id="total_amount"
                                                       name="total_amount" readonly value="0">
                                            </div>

                                            <div class="form-group">
                                                <label for="vat">VAT (%):</label>
                                                <input type="number" class="form-control" id="vat_percent" min="0"
                                                       max="100" value="10" step="0.1">
                                                <input type="hidden" id="vat" name="vat" value="0">
                                            </div>

                                            <div class="form-group">
                                                <label for="discount_percent">Giảm giá (%):</label>
                                                <input type="number" class="form-control" id="discount_percent"
                                                       name="discount_percent" min="0" max="100" value="0" step="0.1">
                                            </div>

                                            <div class="form-group">
                                                <label>Tổng cần trả:</label>
                                                <input type="text" class="form-control" id="final_amount"
                                                       name="final_amount" readonly value="0">
                                            </div>

                                            <div class="form-group">
                                                <label for="paid_amount">Đã trả NCC:</label>
                                                <input type="number" class="form-control" id="paid_amount"
                                                       name="paid_amount" min="0" value="0">
                                            </div>

                                            <div class="form-group">
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <input type="date" class="form-control" id="manufacturing_date" name="manufacturing_date"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">Hạn sử dụng:</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Khởi tạo Select2
            $('.select2').select2();

            // Biến lưu trữ sản phẩm đã chọn
            let selectedProducts = [];

            // Tìm kiếm sản phẩm
            $('#search_product_btn').click(function () {
                console.log('search_product_btn')
                const searchTerm = $('#product_search').val();
                if (searchTerm.length < 2) {
                    alert('Vui lòng nhập ít nhất 2 ký tự để tìm kiếm');
                    return;
                }

                $.ajax({
                    url: "{{ route('api.products.search') }}",
                    method: 'GET',
                    data: {search: searchTerm},
                    success: function (response) {
                        renderSearchResults(response);
                    },
                    error: function (error) {
                        console.error('Error searching products:', error);
                        alert('Đã xảy ra lỗi khi tìm kiếm sản phẩm');
                    }
                });
            });

            // Enter để tìm kiếm
            $('#product_search').keypress(function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#search_product_btn').click();
                }
            });

            // Hiển thị kết quả tìm kiếm
            function renderSearchResults(products) {
                const tbody = $('#product_search_body');
                tbody.empty();

                if (products.length === 0) {
                    tbody.append('<tr><td colspan="4" class="text-center">Không tìm thấy sản phẩm</td></tr>');
                } else {
                    products.forEach(function (product) {
                        const isSelected = selectedProducts.some(p => p.id === product.id);
                        const buttonHtml = isSelected
                            ? '<button type="button" class="btn btn-sm btn-secondary" disabled>Đã chọn</button>'
                            : '<button type="button" class="btn btn-sm btn-success add-product" data-id="' + product.id +
                            '" data-name="' + product.name + '" data-unit="' + product.unit + '">Chọn</button>';

                        tbody.append(`
                        <tr>
                            <td>${product.id}</td>
                            <td>${product.name}</td>
                            <td>${product.unit}</td>
                            <td>${buttonHtml}</td>
                        </tr>
                    `);
                    });
                }

                $('#product_search_results').show();
            }

            // Thêm sản phẩm vào danh sách đã chọn
            $(document).on('click', '.add-product', function () {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productUnit = $(this).data('unit');

                // Kiểm tra sản phẩm đã được chọn chưa
                if (selectedProducts.some(p => p.id === productId)) {
                    alert('Sản phẩm này đã được chọn');
                    return;
                }

                // Thêm sản phẩm vào mảng
                const newProduct = {
                    id: productId,
                    name: productName,
                    unit: productUnit,
                    batch_id: null,
                    batch_info: null,
                    quantity: 1,
                    import_price: 0,
                    total_price: 0
                };

                selectedProducts.push(newProduct);

                // Cập nhật giao diện
                updateSelectedProductsTable();

                // Cập nhật nút trong kết quả tìm kiếm
                $(this).removeClass('btn-success add-product').addClass('btn-secondary').prop('disabled', true).text('Đã chọn');

                // Tải danh sách lô sản phẩm
                loadProductBatches(productId, selectedProducts.length - 1);
            });

            // Xóa sản phẩm khỏi danh sách đã chọn
            $(document).on('click', '.remove-product', function () {
                const index = $(this).data('index');
                const removedProduct = selectedProducts[index];

                // Xóa sản phẩm khỏi mảng
                selectedProducts.splice(index, 1);

                // Cập nhật giao diện
                updateSelectedProductsTable();

                // Cập nhật nút trong kết quả tìm kiếm (nếu sản phẩm vẫn đang hiển thị)
                const searchButton = $('#product_search_body').find(`button[data-id="${removedProduct.id}"]`);
                if (searchButton.length) {
                    searchButton.removeClass('btn-secondary').addClass('btn-success add-product').prop('disabled', false).text('Chọn');
                }

                // Cập nhật tổng tiền
                calculateTotals();
            });

            // Cập nhật bảng sản phẩm đã chọn
            function updateSelectedProductsTable() {
                const tbody = $('#selected_products_body');
                tbody.empty();

                if (selectedProducts.length === 0) {
                    tbody.append('<tr id="no_products_row"><td colspan="8" class="text-center">Chưa có sản phẩm nào được chọn</td></tr>');
                } else {
                    selectedProducts.forEach((product, index) => {
                        const batchDropdown = `
                        <div class="input-group">
                            <select class="form-control batch-select" data-index="${index}">
                                <option value="">-- Chọn lô --</option>
                                <!-- Các lô sẽ được thêm bằng AJAX -->
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary add-batch" data-index="${index}" data-product-id="${product.id}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    `;

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
                                ${batchDropdown}
                                <input type="hidden" name="products[${index}][product_batch_id]" class="batch-id-input" value="${product.batch_id || ''}">
                            </td>
                            <td>${product.unit}</td>
                            <td>
                                <input type="number" class="form-control product-quantity" data-index="${index}" name="products[${index}][quantity]" min="1" value="${product.quantity}">
                            </td>
                            <td>
                                <input type="number" class="form-control product-price" data-index="${index}" name="products[${index}][import_price]" min="0" value="${product.import_price}">
                            </td>
                            <td>
                                <input type="text" class="form-control product-total" data-index="${index}" name="products[${index}][total_price]" readonly value="${product.total_price}">
                            </td>
                        </tr>
                    `);
                    });
                }

                // Cập nhật tổng tiền
                calculateTotals();
            }

            // Tải danh sách lô sản phẩm
            function loadProductBatches(productId, rowIndex) {
                $.ajax({
                    url: `/product-batches/by-product/${productId}`,
                    method: 'GET',
                    success: function (batches) {
                        const select = $(`.batch-select[data-index="${rowIndex}"]`);
                        select.find('option:not(:first)').remove();

                        batches.forEach(function (batch) {
                            const mfgDate = new Date(batch.manufacturing_date).toLocaleDateString('vi-VN');
                            const expDate = new Date(batch.expiry_date).toLocaleDateString('vi-VN');
                            select.append(`<option value="${batch.id}">${batch.batch_number} - NSX: ${mfgDate} - HSD: ${expDate}</option>`);
                        });
                    },
                    error: function (error) {
                        console.error('Error loading product batches:', error);
                    }
                });
            }

            // Xử lý khi chọn lô sản phẩm
            $(document).on('change', '.batch-select', function () {
                const index = $(this).data('index');
                const batchId = $(this).val();

                selectedProducts[index].batch_id = batchId;
                selectedProducts[index].batch_info = batchId ? $(this).find('option:selected').text() : null;

                // Cập nhật input hidden
                $(`.batch-id-input[name="products[${index}][product_batch_id]"]`).val(batchId);
            });

            // Mở modal thêm lô mới
            $(document).on('click', '.add-batch', function () {
                const index = $(this).data('index');
                const productId = $(this).data('product-id');

                $('#batch_product_id').val(productId);
                $('#batch_row_index').val(index);
                $('#batchModal').modal('show');
            });

            // Xử lý form thêm lô mới
            $('#batchForm').submit(function (e) {
                e.preventDefault();

                const productId = $('#batch_product_id').val();
                const rowIndex = $('#batch_row_index').val();
                const batchNumber = $('#batch_number').val();
                const manufacturingDate = $('#manufacturing_date').val();
                const expiryDate = $('#expiry_date').val();

                // Validate dates
                if (new Date(expiryDate) <= new Date(manufacturingDate)) {
                    alert('Hạn sử dụng phải sau ngày sản xuất');
                    return;
                }

                $.ajax({
                    url: "{{ route('product-batches.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        batch_number: batchNumber,
                        manufacturing_date: manufacturingDate,
                        expiry_date: expiryDate
                    },
                    success: function (response) {
                        if (response.success) {
                            // Thêm lô mới vào dropdown
                            const batch = response.batch;
                            const mfgDate = new Date(batch.manufacturing_date).toLocaleDateString('vi-VN');
                            const expDate = new Date(batch.expiry_date).toLocaleDateString('vi-VN');
                            const batchText = `${batch.batch_number} - NSX: ${mfgDate} - HSD: ${expDate}`;

                            const select = $(`.batch-select[data-index="${rowIndex}"]`);
                            select.append(`<option value="${batch.id}">${batchText}</option>`);

                            // Tự động chọn lô mới
                            select.val(batch.id).trigger('change');

                            // Đóng modal
                            $('#batchModal').modal('hide');

                            // Reset form
                            $('#batchForm')[0].reset();

                            // Thông báo
                            alert('Thêm lô sản phẩm thành công!');
                        } else {
                            alert('Đã xảy ra lỗi: ' + response.message);
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = 'Đã xảy ra lỗi khi thêm lô sản phẩm';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Cập nhật số lượng sản phẩm
            $(document).on('input', '.product-quantity', function () {
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
            });

            // Cập nhật giá nhập sản phẩm
            $(document).on('input', '.product-price', function () {
                const index = $(this).data('index');
                const price = parseFloat($(this).val()) || 0;

                if (price < 0) {
                    $(this).val(0);
                    selectedProducts[index].import_price = 0;
                } else {
                    selectedProducts[index].import_price = price;
                }

                // Cập nhật tổng tiền sản phẩm
                updateProductTotal(index);
            });

            // Cập nhật tổng tiền của một sản phẩm
            function updateProductTotal(index) {
                const product = selectedProducts[index];
                const total = product.quantity * product.import_price;

                product.total_price = total;
                $(`.product-total[data-index="${index}"]`).val(total.toLocaleString('vi-VN'));

                // Cập nhật tổng tiền hóa đơn
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

            // Cập nhật tổng tiền khi thay đổi VAT
            $('#vat_percent').on('input', function () {
                calculateTotals();
            });

            // Cập nhật tổng tiền khi thay đổi giảm giá
            $('#discount_percent').on('input', function () {
                calculateTotals();
            });

            // Cập nhật nợ khi thay đổi số tiền đã trả
            $('#paid_amount').on('input', function () {
                const paidAmount = parseFloat($(this).val()) || 0;
                const finalAmount = parseFloat($('#final_amount').val().replace(/\./g, '').replace(',', '.')) || 0;

                if (paidAmount > finalAmount) {
                    $(this).val(finalAmount);
                    alert('Số tiền đã trả không được vượt quá tổng cần trả');
                }

                calculateTotals();
            });

            // Xử lý submit form
            $('#importForm').submit(function (e) {
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
                    $(`input[name="products[${index}][total_price]"]`).val(product.total_price);
                });
            });
        });
    </script>
@endsection


