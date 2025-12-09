<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_code',
        'name',
        'type',
        'phone',
        'email',
        'address',
        'id_number',
        'birth_date',
        'join_date',
        'end_date',
        'salary_type',
        'base_salary',
        'status',
        'photo',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'end_date' => 'date',
        'base_salary' => 'decimal:2',
    ];

    const TYPES = [
        'security' => 'Satpam',
        'cleaning' => 'Petugas Kebersihan',
        'garbage' => 'Petugas Sampah',
        'technical' => 'Petugas Teknik',
    ];

    const SALARY_TYPES = [
        'daily' => 'Harian',
        'weekly' => 'Mingguan',
        'monthly' => 'Bulanan',
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function debts()
    {
        return $this->hasMany(EmployeeDebt::class);
    }

    public function activeDebts()
    {
        return $this->hasMany(EmployeeDebt::class)->where('status', 'active');
    }

    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getSalaryTypeNameAttribute()
    {
        return self::SALARY_TYPES[$this->salary_type] ?? $this->salary_type;
    }

    public function getTotalActiveDebtAttribute()
    {
        return $this->activeDebts()->sum('remaining_amount');
    }

    public static function generateEmployeeCode($type)
    {
        $prefixes = [
            'security' => 'SEC',
            'cleaning' => 'CLN',
            'garbage' => 'GRB',
            'technical' => 'TEC',
        ];
        $prefix = $prefixes[$type] ?? 'EMP';
        $count = self::where('type', $type)->count() + 1;
        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
