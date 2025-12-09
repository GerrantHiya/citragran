<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IplBillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipl_bill_id',
        'billing_type_id',
        'amount',
        'meter_previous',
        'meter_current',
        'usage',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function bill()
    {
        return $this->belongsTo(IplBill::class, 'ipl_bill_id');
    }

    public function billingType()
    {
        return $this->belongsTo(BillingType::class);
    }
}
