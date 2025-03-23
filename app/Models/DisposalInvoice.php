<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposalInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_code',
        'user_id',
        'total_amount',
        'note',
    ];

    /**
     * Quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với DisposalItem
     */
    public function items()
    {
        return $this->hasMany(DisposalItem::class);
    }

    /**
     * Boot model
     */
    protected static function boot()
    {
        parent::boot();

        // Tự động tạo mã phiếu khi tạo mới
        static::creating(function ($invoice) {
            if (!$invoice->invoice_code) {
                $latestInvoice = self::latest()->first();
                $nextId = $latestInvoice ? $latestInvoice->id + 1 : 1;
                $invoice->invoice_code = 'HUY-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
