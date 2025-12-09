<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'type',
        'category',
        'recipient',
        'subject',
        'message',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function markAsSent()
    {
        $this->status = 'sent';
        $this->sent_at = now();
        $this->save();
    }

    public function markAsFailed($errorMessage)
    {
        $this->status = 'failed';
        $this->error_message = $errorMessage;
        $this->save();
    }
}
