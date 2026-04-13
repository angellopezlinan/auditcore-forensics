<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos proveedores anteriores para evitar conflictos si se desea un seed limpio
        // \App\Models\Vendor::where('team_id', 1)->delete();

        // 44 random vendors
        \App\Models\Vendor::factory()->count(44)->create(['team_id' => 1]);

        // 3 pairs of Ghost Vendors (incluyendo homoglifos y nombres similares)
        $pairs = [
            [
                ['legal_name' => 'Global Logistics SL', 'vat_number' => 'B11111111', 'risk_score' => 50],
                ['legal_name' => 'GlobaI Logistics SL', 'vat_number' => 'B11111112', 'risk_score' => 90], // Con 'I' mayúscula
            ],
            [
                ['legal_name' => 'Servicios de Limpieza S.A.', 'vat_number' => 'A88888881', 'risk_score' => 40],
                ['legal_name' => 'Servicios Limpieza S.A.', 'vat_number' => 'A88888882', 'risk_score' => 85],
            ],
            [
                ['legal_name' => 'Tech Solutions', 'vat_number' => 'B99999991', 'risk_score' => 30],
                ['legal_name' => 'Tech Solutioons', 'vat_number' => 'B99999992', 'risk_score' => 95], // Doble 'o'
            ],
        ];

        foreach ($pairs as $pair) {
            foreach ($pair as $data) {
                \App\Models\Vendor::create(array_merge($data, ['team_id' => 1]));
            }
        }
    }
}
