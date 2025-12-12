<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk iuran yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mendapatkan iuran RT aktif
     */
    public static function getActiveFee()
    {
        return self::active()->first();
    }
}
