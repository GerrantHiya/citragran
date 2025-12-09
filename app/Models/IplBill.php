<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IplBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resident_id',
        'bill_number',
        'month',
        'year',
        'total_amount',
        'paid_amount',
        'due_date',
        'paid_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function items()
    {
        return $this->hasMany(IplBillItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && $this->status !== 'paid';
    }

    public function getPeriodNameAttribute()
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$this->month] . ' ' . $this->year;
    }

    public function updateStatus()
    {
        if ($this->paid_amount >= $this->total_amount) {
            $this->status = 'paid';
            $this->paid_date = now();
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif ($this->due_date < now()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'pending';
        }
        $this->save();
    }

    public static function generateBillNumber($year, $month)
    {
        $prefix = 'IPL';
        $count = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1;
        return $prefix . $year . str_pad($month, 2, '0', STR_PAD_LEFT) . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
