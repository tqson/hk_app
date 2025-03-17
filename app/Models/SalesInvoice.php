<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'discount',
        'payment_method',
        'notes',
        'created_at'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(SalesInvoiceDetail::class, 'invoice_id');
    }

    public function returnInvoice()
    {
        return $this->hasOne(ReturnInvoice::class, 'sales_invoice_id');
    }
}
