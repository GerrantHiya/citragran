<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IplRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_land_area',
        'max_land_area',
        'ipl_amount',
        'description',
        'is_active',
    ];

    protected $casts = [
        'min_land_area' => 'decimal:2',
        'max_land_area' => 'decimal:2',
        'ipl_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk rate yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mendapatkan tarif IPL berdasarkan luas tanah
     */
    public static function getRateByLandArea($landArea)
    {
        return self::active()
            ->where('min_land_area', '<=', $landArea)
            ->where(function ($query) use ($landArea) {
                $query->where('max_land_area', '>=', $landArea)
                    ->orWhereNull('max_land_area');
            })
            ->first();
    }

    /**
     * Format range luas tanah
     */
    public function getLandAreaRangeAttribute()
    {
        if ($this->max_land_area) {
            return $this->min_land_area . ' - ' . $this->max_land_area . ' m²';
        }
        return '> ' . $this->min_land_area . ' m²';
    }
}
