<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resident_id',
        'ticket_number',
        'type',
        'subject',
        'description',
        'status',
        'priority',
        'assigned_to',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    const TYPES = [
        'billing' => 'Billing',
        'environment' => 'Lingkungan',
        'dispute' => 'Perselisihan',
        'other' => 'Lainnya',
    ];

    const STATUSES = [
        'received' => 'Diterima',
        'analyzing' => 'Dianalisa',
        'processing' => 'Diproses',
        'rejected' => 'Ditolak',
        'completed' => 'Selesai',
    ];

    const PRIORITIES = [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi',
        'urgent' => 'Mendesak',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }

    public function publicComments()
    {
        return $this->hasMany(ReportComment::class)->where('is_internal', false);
    }

    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPriorityNameAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public static function generateTicketNumber()
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
