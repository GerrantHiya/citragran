<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_debt_id',
        'payroll_id',
        'amount',
        'payment_date',
        'payment_type',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function debt()
    {
        return $this->belongsTo(EmployeeDebt::class, 'employee_debt_id');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
