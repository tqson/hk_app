<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'address',
        'mobile',
        'phone',
        'tax_code',
        'status'
    ];

    /**
     * Kiểm tra nhà cung cấp có đang hoạt động không
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function imports()
    {
        return $this->hasMany(Import::class);
    }


}
