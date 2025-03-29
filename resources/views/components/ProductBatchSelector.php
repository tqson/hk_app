<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductBatchSelector extends Component
{
    public $searchId;
    public $containerId;
    public $tableId;
    public $tableBodyId;
    public $emptyRowId;
    public $colSpan;
    public $label;
    public $emptyMessage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $searchId = 'product_search',
        $containerId = 'added-products-container',
        $tableId = 'selected_products_table',
        $tableBodyId = 'selected_products_body',
        $emptyRowId = 'no_products_row',
        $colSpan = 8,
        $label = null,
        $emptyMessage = null
    ) {

        $this->searchId = $searchId;
        $this->containerId = $containerId;
        $this->tableId = $tableId;
        $this->tableBodyId = $tableBodyId;
        $this->emptyRowId = $emptyRowId;
        $this->colSpan = $colSpan;
        $this->label = $label;
        $this->emptyMessage = $emptyMessage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.product-batch-selector');
    }
}
