<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $residents = [
            ['block_number' => 'A1', 'name' => 'Budi Santoso', 'phone' => '081234567001', 'email' => 'budi@example.com', 'address' => 'Jl. Citra Gran Blok A1'],
            ['block_number' => 'A2', 'name' => 'Dewi Lestari', 'phone' => '081234567002', 'email' => 'dewi@example.com', 'address' => 'Jl. Citra Gran Blok A2'],
            ['block_number' => 'A3', 'name' => 'Ahmad Wijaya', 'phone' => '081234567003', 'email' => 'ahmad@example.com', 'address' => 'Jl. Citra Gran Blok A3'],
            ['block_number' => 'B1', 'name' => 'Siti Rahayu', 'phone' => '081234567004', 'email' => 'siti@example.com', 'address' => 'Jl. Citra Gran Blok B1'],
            ['block_number' => 'B2', 'name' => 'Joko Susilo', 'phone' => '081234567005', 'email' => 'joko@example.com', 'address' => 'Jl. Citra Gran Blok B2'],
            ['block_number' => 'B3', 'name' => 'Rina Marlina', 'phone' => '081234567006', 'email' => 'rina@example.com', 'address' => 'Jl. Citra Gran Blok B3'],
            ['block_number' => 'C1', 'name' => 'Hendra Gunawan', 'phone' => '081234567007', 'email' => 'hendra@example.com', 'address' => 'Jl. Citra Gran Blok C1'],
            ['block_number' => 'C2', 'name' => 'Maya Sari', 'phone' => '081234567008', 'email' => 'maya@example.com', 'address' => 'Jl. Citra Gran Blok C2'],
        ];

        foreach ($residents as $resident) {
            Resident::create($resident);
        }
    }
}
