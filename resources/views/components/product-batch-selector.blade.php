<div class="product-batch-selector">
    <div class="form-group search-product">
        <label for="{{ $searchId }}">{{ $label ?? 'Tìm kiếm sản phẩm:' }}</label>
        <div class="input-group">
            <input type="text" id="{{ $searchId }}" class="form-control product-search-input" placeholder="Nhập tên sản phẩm...">
            <div class="input-group-append">
                <button type="button" class="btn btn-primary" id="search_product_btn">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
        <div class="search-results" style="display: none;"></div>
    </div>

    <!-- Danh sách sản phẩm đã thêm -->
    <div id="{{ $containerId }}" class="added-products-container mb-3"></div>

    <!-- Bảng sản phẩm đã chọn -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="{{ $tableId }}">
            <thead>
            <tr>
                {{ $tableHeader }}
            </tr>
            </thead>
            <tbody id="{{ $tableBodyId }}">
            <!-- Sản phẩm đã chọn sẽ được hiển thị ở đây -->
            <tr id="{{ $emptyRowId }}">
                <td colspan="{{ $colSpan }}" class="text-center">{{ $emptyMessage ?? 'Chưa có sản phẩm nào được chọn' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal chọn lô sản phẩm -->
<div class="modal fade" id="batchSelectionModal" tabindex="-1" role="dialog" aria-labelledby="batchSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batchSelectionModalLabel">Chọn lô cho sản phẩm: <span id="modal-product-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

<!-- Modal thêm lô sản phẩm mới -->
<div class="modal fade" id="newBatchModal" tabindex="-1" role="dialog" aria-labelledby="newBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newBatchModalLabel">Thêm lô sản phẩm mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newBatchForm">
                <div class="modal-body">
                    <input type="hidden" id="new_batch_product_id" name="product_id">

                    <div class="form-group">
                        <label for="new_batch_number">Số lô:</label>
                        <input type="text" class="form-control" id="new_batch_number" name="batch_number" required>
                    </div>

                    <div class="form-group">
                        <label for="new_manufacturing_date">Ngày sản xuất:</label>
                        <input type="date" class="form-control" id="new_manufacturing_date" name="manufacturing_date" required>
                    </div>

                    <div class="form-group">
                        <label for="new_expiry_date">Hạn sử dụng:</label>
                        <input type="date" class="form-control" id="new_expiry_date" name="expiry_date" required>
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
