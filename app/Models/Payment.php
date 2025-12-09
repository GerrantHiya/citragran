<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipl_bill_id',
        'payment_number',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function bill()
    {
        return $this->belongsTo(IplBill::class, 'ipl_bill_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public static function generatePaymentNumber()
    {
        $prefix = 'PAY';
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            $bill = $payment->bill;
            $bill->paid_amount += $payment->amount;
            $bill->updateStatus();
        });
    }
}
