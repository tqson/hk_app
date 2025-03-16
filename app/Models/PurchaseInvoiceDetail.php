<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Get the invoice that owns the detail.
     */
    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'invoice_id');
    }

    /**
     * Get the product that owns the detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
