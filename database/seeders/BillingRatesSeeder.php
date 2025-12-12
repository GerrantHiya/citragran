<?php

namespace Database\Seeders;

use App\Models\IplRate;
use App\Models\RtFee;
use Illuminate\Database\Seeder;

class BillingRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tarif IPL berdasarkan luas tanah
        $iplRates = [
            [
                'name' => 'Tipe 36 (Kecil)',
                'min_land_area' => 0,
                'max_land_area' => 72,
                'ipl_amount' => 150000,
                'description' => 'Untuk luas tanah sampai 72 m²',
            ],
            [
                'name' => 'Tipe 45 (Sedang)',
                'min_land_area' => 72.01,
                'max_land_area' => 100,
                'ipl_amount' => 200000,
                'description' => 'Untuk luas tanah 72 - 100 m²',
            ],
            [
                'name' => 'Tipe 60 (Menengah)',
                'min_land_area' => 100.01,
                'max_land_area' => 150,
                'ipl_amount' => 275000,
                'description' => 'Untuk luas tanah 100 - 150 m²',
            ],
            [
                'name' => 'Tipe 90 (Besar)',
                'min_land_area' => 150.01,
                'max_land_area' => 200,
                'ipl_amount' => 350000,
                'description' => 'Untuk luas tanah 150 - 200 m²',
            ],
            [
                'name' => 'Tipe Premium',
                'min_land_area' => 200.01,
                'max_land_area' => null,
                'ipl_amount' => 500000,
                'description' => 'Untuk luas tanah di atas 200 m²',
            ],
        ];

        foreach ($iplRates as $rate) {
            IplRate::create($rate);
        }

        // Iuran RT (sama untuk semua warga)
        RtFee::create([
            'name' => 'Iuran RT Bulanan',
            'amount' => 50000,
            'description' => 'Iuran RT bulanan untuk semua warga',
            'is_active' => true,
        ]);
    }
}
