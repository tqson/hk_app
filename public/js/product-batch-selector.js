class ProductBatchSelector {
    constructor(options) {
        console.log(document.querySelector('[name="_token"]'))
        this.options = Object.assign({
            searchId: 'product_search',
            containerId: 'added-products-container',
            tableId: 'selected_products_table',
            tableBodyId: 'selected_products_body',
            emptyRowId: 'no_products_row',
            apiSearchUrl: '/api/products/search',
            apiBatchesUrl: '/product-batches/by-product',
            createBatchUrl: '/product-batches',
            csrfToken: document.querySelector('[name="_token"]').getAttribute('content'),
            onProductAdded: null,
            onProductRemoved: null,
            onQuantityChanged: null,
            onPriceChanged: null,
            renderTableRow: null,
            hasStock: false
        }, options);

        this.selectedProducts = [];
        this.addedProducts = {};
        console.log(this.options)
        this.init();
    }

    init() {
        // Tìm kiếm sản phẩm
        $(`#${this.options.searchId}`).on('input', () =>{
            console.log('search')
            return this.handleSearch.bind(this)
        });

        console.log('init')
        $(`#${this.options.searchId}`).on('click', this.handleSearchClick.bind(this));

        // Xử lý khi chọn sản phẩm từ kết quả tìm kiếm
        $(document).on('click', '.search-item', this.handleProductSelect.bind(this));

        // Xử lý nút thêm lô mới trong modal
        $('#add-new-batch-btn').click(this.handleAddNewBatch.bind(this));

        // Xử lý nút thêm các lô đã chọn
        $('#add-selected-batches').click(this.handleAddSelectedBatches.bind(this));

        // Xử lý nút thêm lô cho sản phẩm đã thêm
        $(document).on('click', '.add-more-batch', this.handleAddMoreBatch.bind(this));

        // Xóa sản phẩm khỏi danh sách đã chọn
        $(document).on('click', '.remove-product', this.handleRemoveProduct.bind(this));

        // Cập nhật số lượng sản phẩm
        $(document).on('input', '.product-quantity', this.handleQuantityChange.bind(this));

        // Cập nhật giá nhập sản phẩm
        $(document).on('input', '.product-price', this.handlePriceChange.bind(this));

        // Xử lý form thêm lô mới
        $('#newBatchForm').submit(this.handleNewBatchSubmit.bind(this));

        // Ẩn kết quả tìm kiếm khi click ra ngoài
        $(document).on('click', (e) => {
            if (!$(e.target).closest('.search-product').length) {
                $(`#${this.options.searchId}`).siblings('.search-results').hide();
            }
        });
    }

    handleSearch(e) {
        const searchTerm = $(e.target).val().trim();

        // Nếu xóa hết text, hiển thị 10 sản phẩm mới nhất
        if (searchTerm.length === 0) {
            this.loadRecentProducts();
        } else {
            // Gọi API tìm kiếm sản phẩm
            const params = { search: searchTerm };
            if (this.options.hasStock) {
                params.has_stock = 1;
            }

            $.ajax({
                url: this.options.apiSearchUrl,
                method: 'GET',
                data: params,
                success: (response) => {
                    this.renderSearchResults(response);
                },
                error: (error) => {
                    console.error('Error searching products:', error);
                }
            });
        }
    }

    handleSearchClick(e) {
        // Nếu input trống, hiển thị 10 sản phẩm mới nhất
        if ($(e.target).val().trim() === '') {
            this.loadRecentProducts();
        }
    }

    loadRecentProducts() {
        const params = { recent: 1, limit: 10 };
        if (this.options.hasStock) {
            params.has_stock = 1;
        }

        $.ajax({
            url: this.options.apiSearchUrl,
            method: 'GET',
            data: params,
            success: (response) => {
                this.renderSearchResults(response);
            },
            error: (error) => {
                console.error('Error loading recent products:', error);
            }
        });
    }

    renderSearchResults(products) {
        // Sử dụng ID từ options thay vì selector trực tiếp
        const $results = $(`#${this.options.searchId}`).siblings('.search-results');
        $results.empty();

        if (products.length === 0) {
            $results.append('<div class="search-item">Không tìm thấy sản phẩm</div>');
        } else {
            products.forEach(product => {
                const $item = $(`
                    <div class="search-item" data-id="${product.id}" data-name="${product.name}" data-unit="${product.unit}">
                        <div class="product-name">${product.name}</div>
                        <div class="product-info">
                            <span>Mã SP: ${product.id}</span> |
                            <span>Đơn vị: ${product.unit}</span>
                            ${product.stock_quantity !== undefined ? ` | <span>Tồn kho: ${product.stock_quantity}</span>` : ''}
                        </div>
                    </div>
                `);

                $results.append($item);
            });
        }

        $results.show();
    }

    handleProductSelect(e) {
        const $item = $(e.currentTarget);
        const productId = $item.data('id');
        const productName = $item.data('name');
        const productUnit = $item.data('unit');

        // Lưu thông tin sản phẩm đã thêm
        this.addedProducts[productId] = {
            id: productId,
            name: productName,
            unit: productUnit
        };

        // Lấy danh sách lô cho sản phẩm
        $.ajax({
            url: `${this.options.apiBatchesUrl}/${productId}`,
            method: 'GET',
            success: (response) => {
                this.showBatchSelectionModal(this.addedProducts[productId], response);
            },
            error: (error) => {
                console.error('Error loading product batches:', error);
                alert('Đã xảy ra lỗi khi tải thông tin lô sản phẩm');
            }
        });

        // Ẩn kết quả tìm kiếm
        $(`#${this.options.searchId}`).siblings('.search-results').hide();

        // Xóa text tìm kiếm
        $(`#${this.options.searchId}`).val('');
    }


    showBatchSelectionModal(product, batches) {
        // Cập nhật tiêu đề modal
        $('#modal-product-name').text(product.name);

        // Lưu product_id vào modal để sử dụng sau này
        $('#batchSelectionModal').data('product-id', product.id);

        // Render danh sách lô
        const $tableBody = $('#batches-table-body');
        $tableBody.empty();

        if (batches.length === 0) {
            $tableBody.append(`
                <tr>
                    <td colspan="6" class="text-center">
                        Sản phẩm chưa có lô nào. Vui lòng thêm lô mới.
                    </td>
                </tr>
            `);
        } else {
            batches.forEach(batch => {
                // Kiểm tra xem lô đã được chọn chưa
                const isSelected = this.selectedProducts.some(p =>
                    p.id === product.id && p.batch_id === batch.id
                );

                const mfgDate = this.formatDate(batch.manufacturing_date);
                const expDate = this.formatDate(batch.expiry_date);

                $tableBody.append(`
                    <tr>
                        <td>
                            <input type="checkbox" class="batch-checkbox"
                                data-batch-id="${batch.id}"
                                data-batch-number="${batch.batch_number}"
                                data-mfg-date="${batch.manufacturing_date}"
                                data-exp-date="${batch.expiry_date}"
                                ${isSelected ? 'checked disabled' : ''}>
                        </td>
                        <td>${batch.batch_number}</td>
                        <td>${mfgDate}</td>
                        <td>${expDate}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm batch-quantity"
                                min="1" value="1" ${isSelected ? 'disabled' : ''}>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm batch-price"
                                min="0" value="0" ${isSelected ? 'disabled' : ''}>
                        </td>
                    </tr>
                `);
            });
        }

        // Hiển thị modal
        $('#batchSelectionModal').modal('show');
    }

    handleAddNewBatch() {
        const productId = $('#batchSelectionModal').data('product-id');

        // Cập nhật product_id cho form thêm lô mới
        $('#new_batch_product_id').val(productId);

        // Ẩn modal chọn lô
        $('#batchSelectionModal').modal('hide');

        // Hiển thị modal thêm lô mới
        $('#newBatchModal').modal('show');
    }

    handleAddSelectedBatches() {
        const productId = $('#batchSelectionModal').data('product-id');
        const product = this.addedProducts[productId];
        let batchesAdded = false;

        // Lặp qua tất cả các checkbox đã chọn
        $('.batch-checkbox:checked:not(:disabled)').each(function () {
            const $row = $(this).closest('tr');
            const batchId = $(this).data('batch-id');
            const batchNumber = $(this).data('batch-number');
            const mfgDate = $(this).data('mfg-date');
            const expDate = $(this).data('exp-date');
            const quantity = parseInt($row.find('.batch-quantity').val()) || 1;
            const price = parseFloat($row.find('.batch-price').val()) || 0;

            // Thêm sản phẩm với lô đã chọn vào danh sách
            this.selectedProducts.push({
                id: productId,
                name: product.name,
                unit: product.unit,
                batch_id: batchId,
                batch_number: batchNumber,
                manufacturing_date: mfgDate,
                expiry_date: expDate,
                quantity: quantity,
                import_price: price,
                total_price: quantity * price
            });

            batchesAdded = true;
        }.bind(this));

        if (batchesAdded) {
            // Cập nhật giao diện
            this.updateSelectedProductsTable();
            this.updateAddedProductsList();

            // Đóng modal
            $('#batchSelectionModal').modal('hide');

            // Callback
            if (typeof this.options.onProductAdded === 'function') {
                this.options.onProductAdded(this.selectedProducts);
            }
        } else {
            alert('Vui lòng chọn ít nhất một lô sản phẩm');
        }
    }

    handleAddMoreBatch(e) {
        const productId = $(e.currentTarget).data('product-id');
        const product = this.addedProducts[productId];

        // Lấy danh sách lô cho sản phẩm
        $.ajax({
            url: `${this.options.apiBatchesUrl}/${productId}`,
            method: 'GET',
            success: (response) => {
                this.showBatchSelectionModal(product, response);
            },
            error: (error) => {
                console.error('Error loading product batches:', error);
                alert('Đã xảy ra lỗi khi tải thông tin lô sản phẩm');
            }
        });
    }

    handleRemoveProduct(e) {
        const index = $(e.currentTarget).data('index');

        // Xóa sản phẩm khỏi mảng
        this.selectedProducts.splice(index, 1);

        // Cập nhật giao diện
        this.updateSelectedProductsTable();
        this.updateAddedProductsList();

        // Callback
        if (typeof this.options.onProductRemoved === 'function') {
            this.options.onProductRemoved(this.selectedProducts);
        }
    }

    handleQuantityChange(e) {
        const index = $(e.currentTarget).data('index');
        const quantity = parseInt($(e.currentTarget).val()) || 0;

        if (quantity < 1) {
            $(e.currentTarget).val(1);
            this.selectedProducts[index].quantity = 1;
        } else {
            this.selectedProducts[index].quantity = quantity;
        }

        // Cập nhật tổng tiền sản phẩm
        this.updateProductTotal(index);

        // Callback
        if (typeof this.options.onQuantityChanged === 'function') {
            this.options.onQuantityChanged(this.selectedProducts, index);
        }
    }

    handlePriceChange(e) {
        const index = $(e.currentTarget).data('index');
        const price = parseFloat($(e.currentTarget).val()) || 0;

        if (price < 0) {
            $(e.currentTarget).val(0);
            this.selectedProducts[index].import_price = 0;
        } else {
            this.selectedProducts[index].import_price = price;
        }

        // Cập nhật tổng tiền sản phẩm
        this.updateProductTotal(index);

        // Callback
        if (typeof this.options.onPriceChanged === 'function') {
            this.options.onPriceChanged(this.selectedProducts, index);
        }
    }

    updateProductTotal(index) {
        const product = this.selectedProducts[index];
        const total = product.quantity * product.import_price;

        product.total_price = total;
        $(`.product-total[data-index="${index}"]`).val(total.toLocaleString('vi-VN'));
    }

    handleNewBatchSubmit(e) {
        e.preventDefault();

        const productId = $('#new_batch_product_id').val();
        const batchNumber = $('#new_batch_number').val();
        const manufacturingDate = $('#new_manufacturing_date').val();
        const expiryDate = $('#new_expiry_date').val();

        // Validate dates
        if (new Date(expiryDate) <= new Date(manufacturingDate)) {
            alert('Hạn sử dụng phải sau ngày sản xuất');
            return;
        }

        $.ajax({
            url: this.options.createBatchUrl,
            method: 'POST',
            data: {
                _token: this.options.csrfToken,
                product_id: productId,
                batch_number: batchNumber,
                manufacturing_date: manufacturingDate,
                expiry_date: expiryDate
            },
            success: (response) => {
                if (response.success) {
                    // Thông báo
                    alert('Thêm lô sản phẩm thành công!');

                    // Reset form
                    $('#newBatchForm')[0].reset();

                    // Đóng modal
                    $('#newBatchModal').modal('hide');

                    // Mở lại modal chọn lô và tải lại danh sách lô
                    $.ajax({
                        url: `${this.options.apiBatchesUrl}/${productId}`,
                        method: 'GET',
                        success: (batches) => {
                            this.showBatchSelectionModal(this.addedProducts[productId], batches);
                        }
                    });
                } else {
                    alert('Đã xảy ra lỗi: ' + response.message);
                }
            },
            error: (xhr) => {
                let errorMessage = 'Đã xảy ra lỗi khi thêm lô sản phẩm';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                alert(errorMessage);
            }
        });
    }

    updateSelectedProductsTable() {
        const $tbody = $(`#${this.options.tableBodyId}`);
        $tbody.empty();

        if (this.selectedProducts.length === 0) {
            $tbody.append(`<tr id="${this.options.emptyRowId}"><td colspan="${this.options.colSpan}" class="text-center">Chưa có sản phẩm nào được chọn</td></tr>`);
        } else {
            this.selectedProducts.forEach((product, index) => {
                if (typeof this.options.renderTableRow === 'function') {
                    // Sử dụng hàm render tùy chỉnh nếu được cung cấp
                    const rowHtml = this.options.renderTableRow(product, index);
                    $tbody.append(rowHtml);
                } else {
                    // Sử dụng render mặc định
                    const mfgDate = this.formatDate(product.manufacturing_date);
                    const expDate = this.formatDate(product.expiry_date);
                    const batchInfo = `${product.batch_number} - NSX: ${mfgDate} - HSD: ${expDate}`;

                    $tbody.append(`
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
                                <input type="number" class="form-control product-price" data-index="${index}"
                                    name="products[${index}][import_price]" min="0" value="${product.import_price}">
                            </td>
                            <td>
                                <input type="text" class="form-control product-total" data-index="${index}"
                                    name="products[${index}][total_price]" readonly value="${product.total_price.toLocaleString('vi-VN')}">
                            </td>
                        </tr>
                    `);
                }
            });
        }
    }

    updateAddedProductsList() {
        const $container = $(`#${this.options.containerId}`);
        $container.empty();

        // Nhóm theo ID sản phẩm
        const productGroups = {};
        this.selectedProducts.forEach(product => {
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

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }

    // Phương thức để lấy danh sách sản phẩm đã chọn
    getSelectedProducts() {
        return this.selectedProducts;
    }

    // Phương thức để đặt lại danh sách sản phẩm đã chọn
    setSelectedProducts(products) {
        this.selectedProducts = products;
        this.updateSelectedProductsTable();
        this.updateAddedProductsList();
    }

    // Phương thức để thêm một sản phẩm vào danh sách
    addProduct(product) {
        this.selectedProducts.push(product);
        this.updateSelectedProductsTable();
        this.updateAddedProductsList();
    }

    // Phương thức để xóa một sản phẩm khỏi danh sách
    removeProduct(index) {
        this.selectedProducts.splice(index, 1);
        this.updateSelectedProductsTable();
        this.updateAddedProductsList();
    }

    // Phương thức để xóa tất cả sản phẩm
    clearProducts() {
        this.selectedProducts = [];
        this.updateSelectedProductsTable();
        this.updateAddedProductsList();
    }
}
