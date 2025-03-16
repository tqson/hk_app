<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'total_amount',
        'paid_amount',
        'debt',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'debt' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Get the supplier that owns the purchase invoice.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the details for the purchase invoice.
     */
    public function details()
    {
        return $this->hasMany(PurchaseInvoiceDetail::class, 'invoice_id');
    }
}
