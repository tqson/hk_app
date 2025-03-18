@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tạo hóa đơn trả hàng</h5>
                        <a href="{{ route('returns.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="returnForm">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sales_invoice_id">Hóa đơn bán hàng:</label>
                                        <div class="input-group">
                                            <select class="form-control me-4" id="sales_invoice_id" name="sales_invoice_id" required>
                                                <option value="">-- Chọn hóa đơn --</option>
                                                @foreach($recentInvoices as $invoice)
                                                    <option value="{{ $invoice->id }}">HD{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }} - {{ Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#searchInvoiceModal">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="purchase_date">Ngày mua hàng:</label>
                                        <input type="text" class="form-control" id="purchase_date" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="product_selection">Thông tin sản phẩm:</label>
                                        <div class="input-group">
                                            <select class="form-control" id="product_selection" disabled>
                                                <option value="">-- Chọn sản phẩm --</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="addProductBtn" disabled>
                                                    <i class="fas fa-plus"></i> Thêm sản phẩm
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-hover" id="returnProductsTable">
                                    <thead class="thead-light">
                                    <tr>
                                        <th width="5%">STT</th>
                                        <th width="25%">Tên sản phẩm</th>
                                        <th width="10%">Đơn vị</th>
                                        <th width="10%">Số lô</th>
                                        <th width="15%">Số lượng đã mua</th>
                                        <th width="15%">Số lượng hoàn trả</th>
                                        <th width="15%">Giá trị hoàn</th>
                                        <th width="5%">Xóa</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr id="noProductRow">
                                        <td colspan="8" class="text-center">Chưa có sản phẩm nào được chọn</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="notes">Ghi chú:</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label class="col-sm-6 col-form-label font-weight-bold">Tổng tiền hoàn trả:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control-plaintext text-right font-weight-bold" id="total_amount_display" value="0 VND" readonly>
                                                    <input type="hidden" id="total_amount" name="total_amount" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-secondary me-2" id="cancelBtn">Hủy bỏ</button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">Tạo hóa đơn trả hàng</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal tìm kiếm hóa đơn -->
    <div class="modal fade" id="searchInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="searchInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchInvoiceModalLabel">Tìm kiếm hóa đơn</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="invoice_search">Nhập mã hóa đơn:</label>
                        <input type="text" class="form-control" id="invoice_search" placeholder="Nhập mã hóa đơn...">
                    </div>
                    <div id="search_results" class="mt-3">
                        <!-- Kết quả tìm kiếm sẽ hiển thị ở đây -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let selectedProducts = [];
            let invoiceProducts = [];

            // Xử lý khi chọn hóa đơn bán hàng
            $('#sales_invoice_id').change(function() {
                const invoiceId = $(this).val();
                if (invoiceId) {
                    fetchInvoiceDetails(invoiceId);
                } else {
                    resetProductSelection();
                }
            });

            // Hàm lấy chi tiết hóa đơn
            function fetchInvoiceDetails(invoiceId) {
                $.ajax({
                    url: "{{ url('returns/get-invoice-details') }}/" + invoiceId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Hiển thị ngày mua hàng
                        const purchaseDate = new Date(response.created_at);
                        $('#purchase_date').val(formatDate(purchaseDate));

                        // Lưu danh sách sản phẩm và cập nhật dropdown
                        invoiceProducts = response.details;
                        updateProductDropdown();

                        // Kích hoạt nút thêm sản phẩm
                        $('#product_selection').prop('disabled', false);
                        $('#addProductBtn').prop('disabled', false);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        alert('Không thể lấy thông tin hóa đơn. Vui lòng thử lại.');
                    }
                });
            }

            // Cập nhật dropdown sản phẩm
            function updateProductDropdown() {
                const dropdown = $('#product_selection');
                dropdown.empty();
                dropdown.append('<option value="">-- Chọn sản phẩm --</option>');

                invoiceProducts.forEach(function(item) {
                    // Kiểm tra xem sản phẩm đã được thêm vào danh sách chưa
                    const isAdded = selectedProducts.some(p => p.id === item.product_id);
                    if (!isAdded) {
                        dropdown.append(`<option value="${item.product_id}"
                                    data-detail-id="${item.id}"
                                    data-name="${item.product.name}"
                                    data-unit="${item.product.unit}"
                                    data-batch="${item.product.batch_number || 'N/A'}"
                                    data-quantity="${item.quantity}"
                                    data-price="${item.price}">
                                    ${item.product.name} - SL: ${item.quantity}
                                    </option>`
                        );
                    }
                });
            }

            // Xử lý khi nhấn nút thêm sản phẩm
            $('#addProductBtn').click(function() {
                const selectedOption = $('#product_selection option:selected');
                if (selectedOption.val()) {
                    const productId = selectedOption.val();
                    const detailId = selectedOption.data('detail-id');
                    const productName = selectedOption.data('name');
                    const productUnit = selectedOption.data('unit');
                    const productBatch = selectedOption.data('batch');
                    const purchasedQuantity = selectedOption.data('quantity');
                    const productPrice = selectedOption.data('price');

                    // Thêm sản phẩm vào danh sách đã chọn
                    const product = {
                        id: parseInt(productId),
                        detail_id: detailId,
                        name: productName,
                        unit: productUnit,
                        batch: productBatch,
                        purchased_quantity: purchasedQuantity,
                        return_quantity: 1, // Mặc định là 1
                        price: productPrice
                    };

                    selectedProducts.push(product);

                    // Cập nhật bảng sản phẩm
                    updateProductTable();

                    // Cập nhật dropdown
                    updateProductDropdown();

                    // Cập nhật tổng tiền
                    calculateTotal();
                }
            });

            // Cập nhật bảng sản phẩm
            function updateProductTable() {
                const tableBody = $('#returnProductsTable tbody');
                tableBody.empty();

                if (selectedProducts.length === 0) {
                    tableBody.append(`
                    <tr id="noProductRow">
                        <td colspan="8" class="text-center">Chưa có sản phẩm nào được chọn</td>
                    </tr>
                `);
                    return;
                }

                selectedProducts.forEach(function(product, index) {
                    const returnValue = product.return_quantity * product.price;

                    tableBody.append(`
                    <tr data-index="${index}">
                        <td>${index + 1}</td>
                        <td>${product.name}</td>
                        <td>${product.unit}</td>
                        <td>${product.batch}</td>
                        <td>${product.purchased_quantity}</td>
                        <td>
                            <input type="number" class="form-control return-quantity"
                                   min="1" max="${product.purchased_quantity}"
                                   value="${product.return_quantity}"
                                   data-index="${index}">
                        </td>
                        <td class="text-right return-value">${formatCurrency(returnValue)}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-product" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
                });

                // Đăng ký sự kiện cho các phần tử mới
                registerTableEvents();
            }

            // Đăng ký sự kiện cho bảng sản phẩm
            function registerTableEvents() {
                // Xử lý khi thay đổi số lượng hoàn trả
                $('.return-quantity').change(function() {
                    const index = $(this).data('index');
                    const newQuantity = parseInt($(this).val());
                    const maxQuantity = selectedProducts[index].purchased_quantity;

                    // Kiểm tra giới hạn số lượng
                    if (newQuantity < 1) {
                        $(this).val(1);
                        selectedProducts[index].return_quantity = 1;
                    } else if (newQuantity > maxQuantity) {
                        $(this).val(maxQuantity);
                        selectedProducts[index].return_quantity = maxQuantity;
                    } else {
                        selectedProducts[index].return_quantity = newQuantity;
                    }

                    // Cập nhật giá trị hoàn trả
                    const returnValue = selectedProducts[index].return_quantity * selectedProducts[index].price;
                    $(this).closest('tr').find('.return-value').text(formatCurrency(returnValue));

                    // Cập nhật tổng tiền
                    calculateTotal();
                });

                // Xử lý khi nhấn nút xóa sản phẩm
                $('.remove-product').click(function() {
                    const index = $(this).data('index');
                    selectedProducts.splice(index, 1);
                    updateProductTable();
                    updateProductDropdown();
                    calculateTotal();
                });
            }

            // Tính tổng tiền hoàn trả
            function calculateTotal() {
                let total = 0;
                selectedProducts.forEach(function(product) {
                    total += product.return_quantity * product.price;
                });

                $('#total_amount').val(total);
                $('#total_amount_display').val(formatCurrency(total));
            }

            // Reset form khi chọn hóa đơn mới
            function resetProductSelection() {
                $('#purchase_date').val('');
                $('#product_selection').empty().append('<option value="">-- Chọn sản phẩm --</option>').prop('disabled', true);
                $('#addProductBtn').prop('disabled', true);
                selectedProducts = [];
                invoiceProducts = [];
                updateProductTable();
                calculateTotal();
            }

            // Xử lý tìm kiếm hóa đơn
            $('#invoice_search').on('input', function() {
                const searchTerm = $(this).val().trim();
                if (searchTerm.length > 0) {
                    $.ajax({
                        url: "{{ route('returns.search-invoices') }}",
                        type: 'GET',
                        data: { search: searchTerm },
                        dataType: 'json',
                        success: function(response) {
                            displaySearchResults(response);
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                        }
                    });
                } else {
                    $('#search_results').empty();
                }
            });

            // Hiển thị kết quả tìm kiếm
            function displaySearchResults(invoices) {
                const resultsDiv = $('#search_results');
                resultsDiv.empty();

                if (invoices.length === 0) {
                    resultsDiv.append('<p class="text-center">Không tìm thấy hóa đơn nào</p>');
                    return;
                }

                const list = $('<div class="list-group"></div>');

                invoices.forEach(function(invoice) {
                    const invoiceDate = new Date(invoice.created_at);
                    const invoiceId = 'HD' + invoice.id.toString().padStart(6, '0');

                    list.append(`
                    <a href="#" class="list-group-item list-group-item-action select-invoice" data-id="${invoice.id}" data-dismiss="modal">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">${invoiceId}</h5>
                            <small>${formatDate(invoiceDate)}</small>
                        </div>
                        <p class="mb-1">Tổng tiền: ${formatCurrency(invoice.total_amount - invoice.discount)}</p>
                    </a>
                `);
                });

                resultsDiv.append(list);

                // Xử lý khi chọn hóa đơn từ kết quả tìm kiếm
                $('.select-invoice').click(function() {
                    const invoiceId = $(this).data('id');
                    $('#sales_invoice_id').val(invoiceId).trigger('change');
                });
            }

            // Xử lý khi nhấn nút hủy bỏ
            $('#cancelBtn').click(function() {
                if (confirm('Bạn có chắc muốn hủy bỏ? Tất cả dữ liệu sẽ bị mất.')) {
                    window.location.href = "{{ route('returns.index') }}";
                }
            });

            // Xử lý khi submit form
            $('#returnForm').submit(function(e) {
                e.preventDefault();

                if (selectedProducts.length === 0) {
                    alert('Vui lòng chọn ít nhất một sản phẩm để hoàn trả.');
                    return;
                }

                // Chuẩn bị dữ liệu
                const formData = {
                    _token: $('input[name="_token"]').val(),
                    sales_invoice_id: $('#sales_invoice_id').val(),
                    products: JSON.stringify(selectedProducts),
                    total_amount: $('#total_amount').val(),
                    notes: $('#notes').val()
                };

                // Gửi request
                $.ajax({
                    url: "{{ route('returns.store') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: response.message,
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Xem danh sách',
                                cancelButtonText: 'Tạo mới'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('returns.index') }}";
                                } else {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire('Lỗi!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire('Lỗi!', 'Đã xảy ra lỗi khi xử lý yêu cầu.', 'error');
                    }
                });
            });

            // Hàm định dạng tiền tệ
            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                    .format(amount)
                    .replace(/\s₫/, ' VND');
            }

            // Hàm định dạng ngày tháng
            function formatDate(date) {
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                const hours = date.getHours().toString().padStart(2, '0');
                const minutes = date.getMinutes().toString().padStart(2, '0');

                return `${day}/${month}/${year} ${hours}:${minutes}`;
            }
        });
    </script>
@endsection
