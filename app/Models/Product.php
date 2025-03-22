<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'unit',
        'status',
        'stock',
        'price',
        'import_price',
        'description',
        'image',
        'sku',
        'barcode',
    ];

    // Quan hệ với Category
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    // Quan hệ với ProductBatch
    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    // Quan hệ với ImportItem
    public function importItems()
    {
        return $this->hasMany(ImportItem::class);
    }

    // Lấy tổng số lượng tồn kho (bao gồm cả các lô)
    public function getTotalStockAttribute()
    {
        return $this->batches()->sum('quantity') + $this->stock;
    }
}
