<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sales_invoice_id',
        'total_amount',
        'notes',
        'created_at'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function details()
    {
        return $this->hasMany(ReturnInvoiceDetail::class, 'return_invoice_id');
    }
}
