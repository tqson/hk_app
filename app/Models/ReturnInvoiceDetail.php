<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInvoiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_invoice_id',
        'product_id',
        'quantity',
        'price'
    ];

    public $timestamps = false;

    public function returnInvoice()
    {
        return $this->belongsTo(ReturnInvoice::class, 'return_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
