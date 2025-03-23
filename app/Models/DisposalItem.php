<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'disposal_invoice_id',
        'product_id',
        'product_batch_id',
        'quantity',
        'price',
        'total_price',
        'reason',
    ];

    /**
     * Quan hệ với DisposalInvoice
     */
    public function invoice()
    {
        return $this->belongsTo(DisposalInvoice::class, 'disposal_invoice_id');
    }

    /**
     * Quan hệ với Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với ProductBatch
     */
    public function batch()
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }
}
