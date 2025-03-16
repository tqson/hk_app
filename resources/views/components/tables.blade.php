<div class="mb-4">
    <h4>Basic Table</h4>
    <div class="ant-table-wrapper mb-3">
        <table class="ant-table">
            <thead class="ant-table-thead">
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="ant-table-tbody">
            <tr>
                <td>John Brown</td>
                <td>32</td>
                <td>New York No. 1 Lake Park</td>
                <td>
                    <button class="ant-btn ant-btn-sm">View</button>
                    <button class="ant-btn ant-btn-sm ant-btn-danger">Delete</button>
                </td>
            </tr>
            <tr>
                <td>Jim Green</td>
                <td>42</td>
                <td>London No. 1 Lake Park</td>
                <td>
                    <button class="ant-btn ant-btn-sm">View</button>
                    <button class="ant-btn ant-btn-sm ant-btn-danger">Delete</button>
                </td>
            </tr>
            <tr>
                <td>Joe Black</td>
                <td>32</td>
                <td>Sidney No. 1 Lake Park</td>
                <td>
                    <button class="ant-btn ant-btn-sm">View</button>
                    <button class="ant-btn ant-btn-sm ant-btn-danger">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <h4>Bordered Table</h4>
    <div class="ant-table-wrapper">
        <table class="ant-table ant-table-bordered">
            <thead class="ant-table-thead">
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Address</th>
            </tr>
            </thead>
            <tbody class="ant-table-tbody">
            <tr>
                <td>John Brown</td>
                <td>32</td>
                <td>New York No. 1 Lake Park</td>
            </tr>
            <tr>
                <td>Jim Green</td>
                <td>42</td>
                <td>London No. 1 Lake Park</td>
            </tr>
            <tr>
                <td>Joe Black</td>
                <td>32</td>
                <td>Sidney No. 1 Lake Park</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle sidebar collapse
        const siderTrigger = document.querySelector('.ant-layout-sider-trigger');
        if (siderTrigger) {
            siderTrigger.addEventListener('click', function () {
                const sider = document.querySelector('.ant-layout-sider');
                sider.classList.toggle('ant-layout-sider-collapsed');
            });
        }

        // Dropdown menus
        const dropdownTriggers = document.querySelectorAll('.ant-dropdown-trigger');
        dropdownTriggers.forEach(trigger => {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                const dropdown = this.nextElementSibling;
                if (dropdown) {
                    dropdown.classList.toggle('ant-dropdown-open');
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            const dropdowns = document.querySelectorAll('.ant-dropdown-open');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(e.target) &&
                    !e.target.classList.contains('ant-dropdown-trigger')) {
                    dropdown.classList.remove('ant-dropdown-open');
                }
            });
        });

        // Form validation example
        const forms = document.querySelectorAll('.ant-form');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                let hasError = false;
                const requiredInputs = form.querySelectorAll('.ant-form-item-required');

                requiredInputs.forEach(label => {
                    const formItem = label.closest('.ant-form-item');
                    const input = formItem.querySelector('.ant-input');
                    const errorMsg = formItem.querySelector('.ant-form-explain');

                    if (input && !input.value.trim()) {
                        hasError = true;
                        formItem.classList.add('ant-form-item-has-error');
                        if (errorMsg) {
                            errorMsg.textContent = 'This field is required';
                        }
                    } else if (formItem) {
                        formItem.classList.remove('ant-form-item-has-error');
                    }
                });

                if (hasError) {
                    e.preventDefault();
                }
            });
        });

        // Input focus effects
        const inputs = document.querySelectorAll('.ant-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.classList.add('ant-input-focused');
            });

            input.addEventListener('blur', function () {
                this.parentElement.classList.remove('ant-input-focused');
            });
        });
    });
</script>
