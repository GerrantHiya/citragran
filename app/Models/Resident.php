<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'block_number',
        'phone',
        'whatsapp',
        'email',
        'address',
        'status',
        'move_in_date',
    ];

    protected $casts = [
        'move_in_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function iplBills()
    {
        return $this->hasMany(IplBill::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function getLatestBillAttribute()
    {
        return $this->iplBills()->latest()->first();
    }

    public function getUnpaidBillsAttribute()
    {
        return $this->iplBills()->whereIn('status', ['pending', 'partial', 'overdue'])->get();
    }

    public function getTotalOutstandingAttribute()
    {
        return $this->iplBills()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->sum(\DB::raw('total_amount - paid_amount'));
    }
}
