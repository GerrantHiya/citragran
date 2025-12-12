<?php

namespace Database\Seeders;

use App\Models\RtFee;
use Illuminate\Database\Seeder;

class BillingRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Iuran RT (sama untuk semua warga)
        RtFee::create([
            'name' => 'Iuran RT Bulanan',
            'amount' => 50000,
            'description' => 'Iuran RT bulanan untuk semua warga',
            'is_active' => true,
        ]);
    }
}
