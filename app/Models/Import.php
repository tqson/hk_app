<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_code',
        'supplier_id',
        'expected_date',
        'total_amount',
        'vat',
        'discount_percent',
        'final_amount',
        'paid_amount',
        'debt_amount',
    ];

    protected $casts = [
        'expected_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(ImportItem::class);
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($import) {
            $import->import_code = 'IMP-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        });
    }
}
