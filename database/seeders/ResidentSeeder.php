<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $residents = [
            ['block_number' => 'A1', 'name' => 'Budi Santoso', 'land_area' => 60, 'phone' => '081234567001', 'email' => 'budi@example.com', 'address' => 'Jl. Citra Gran Blok A1'],
            ['block_number' => 'A2', 'name' => 'Dewi Lestari', 'land_area' => 72, 'phone' => '081234567002', 'email' => 'dewi@example.com', 'address' => 'Jl. Citra Gran Blok A2'],
            ['block_number' => 'A3', 'name' => 'Ahmad Wijaya', 'land_area' => 90, 'phone' => '081234567003', 'email' => 'ahmad@example.com', 'address' => 'Jl. Citra Gran Blok A3'],
            ['block_number' => 'B1', 'name' => 'Siti Rahayu', 'land_area' => 120, 'phone' => '081234567004', 'email' => 'siti@example.com', 'address' => 'Jl. Citra Gran Blok B1'],
            ['block_number' => 'B2', 'name' => 'Joko Susilo', 'land_area' => 150, 'phone' => '081234567005', 'email' => 'joko@example.com', 'address' => 'Jl. Citra Gran Blok B2'],
            ['block_number' => 'B3', 'name' => 'Rina Marlina', 'land_area' => 180, 'phone' => '081234567006', 'email' => 'rina@example.com', 'address' => 'Jl. Citra Gran Blok B3'],
            ['block_number' => 'C1', 'name' => 'Hendra Gunawan', 'land_area' => 200, 'phone' => '081234567007', 'email' => 'hendra@example.com', 'address' => 'Jl. Citra Gran Blok C1'],
            ['block_number' => 'C2', 'name' => 'Maya Sari', 'land_area' => 250, 'phone' => '081234567008', 'email' => 'maya@example.com', 'address' => 'Jl. Citra Gran Blok C2'],
        ];

        foreach ($residents as $resident) {
            Resident::create($resident);
        }
    }
}
