<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_number',
        'manufacturing_date',
        'expiry_date',
    ];

    protected $casts = [
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function importItems()
    {
        return $this->hasMany(ImportItem::class);
    }
}
