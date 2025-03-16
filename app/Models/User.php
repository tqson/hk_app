<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'full_name',
        'date_of_birth',
        'address',
        'phone',
        'email',
        'bank_name',
        'bank_account_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
//        'password' => 'hashed',
        'created_at' => 'datetime',
    ];

    /**
     * Get the sales invoices for the user.
     */
    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    /**
     * Get the user's avatar URL.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }

        // Generate avatar from name if no avatar is set
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&size=200';
    }
}
