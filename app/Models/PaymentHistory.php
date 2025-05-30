<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'import_payment_histories';

    protected $fillable = [
        'import_id',
        'amount',
        'remaining_debt',
    ];

    public function import()
    {
        return $this->belongsTo(Import::class);
    }
}
