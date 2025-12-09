<?php

namespace Database\Seeders;

use App\Models\BillingType;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@citragran.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff Perumahan',
            'email' => 'staff@citragran.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'is_active' => true,
        ]);

        // Create Billing Types
        $billingTypes = [
            ['name' => 'Air PAM', 'code' => 'water', 'default_amount' => 50000, 'description' => 'Iuran penggunaan air PAM'],
            ['name' => 'Kebersihan', 'code' => 'cleaning', 'default_amount' => 100000, 'description' => 'Iuran kebersihan lingkungan'],
            ['name' => 'Sampah', 'code' => 'garbage', 'default_amount' => 50000, 'description' => 'Iuran pengangkutan sampah'],
            ['name' => 'Security', 'code' => 'security', 'default_amount' => 150000, 'description' => 'Iuran keamanan perumahan'],
        ];

        foreach ($billingTypes as $type) {
            BillingType::create($type);
        }

        // Create Expense Categories
        $expenseCategories = [
            ['name' => 'Operasional', 'code' => 'operational', 'description' => 'Biaya operasional harian'],
            ['name' => 'Pemeliharaan', 'code' => 'maintenance', 'description' => 'Biaya pemeliharaan fasilitas'],
            ['name' => 'Perbaikan', 'code' => 'repair', 'description' => 'Biaya perbaikan kerusakan'],
            ['name' => 'Peralatan', 'code' => 'equipment', 'description' => 'Pembelian peralatan'],
            ['name' => 'ATK', 'code' => 'stationery', 'description' => 'Alat tulis kantor'],
            ['name' => 'Listrik', 'code' => 'electricity', 'description' => 'Biaya listrik fasilitas umum'],
            ['name' => 'Lain-lain', 'code' => 'other', 'description' => 'Pengeluaran lainnya'],
        ];

        foreach ($expenseCategories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
