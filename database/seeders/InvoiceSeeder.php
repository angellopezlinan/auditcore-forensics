<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = \App\Models\Vendor::all();
        if ($vendors->isEmpty()) {
            return;
        }

        // 915 Random Invoices (para llegar a 1000 con los 85 de los traps)
        // 1000 - 20 (dup) - 15 (thr) - 50 (wkd) = 915
        \App\Models\Invoice::factory()->count(915)->create([
            'team_id' => 1,
            'vendor_id' => fn() => $vendors->random()->id,
            'file_path' => 'invoices/dummy.pdf', // Para evitar 404
        ]);

        // TRAMPA 1: 10 facturas duplicadas (20 registros)
        for ($i = 0; $i < 10; $i++) {
            $num = 'DUP-X-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT);
            $amount = rand(1000, 2500);
            $vendorId = $vendors->random()->id;
            
            \App\Models\Invoice::factory()->count(2)->create([
                'invoice_number' => $num,
                'total_amount' => $amount,
                'vendor_id' => $vendorId,
                'file_path' => 'invoices/dummy.pdf',
            ]);
        }

        // TRAMPA 2: 15 Facturas con importes "umbral" (2.990€ a 2.999€)
        for ($i = 0; $i < 15; $i++) {
            \App\Models\Invoice::factory()->create([
                'total_amount' => rand(2990 * 100, 2999 * 100) / 100,
                'invoice_number' => 'UMBRAL-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                'vendor_id' => $vendors->random()->id,
                'file_path' => 'invoices/dummy.pdf',
            ]);
        }

        // TRAMPA 3: 50 Facturas en domingos o festivos (5% aprox)
        for ($i = 0; $i < 50; $i++) {
            $date = \Illuminate\Support\Carbon::parse(fake()->dateTimeBetween('-1 year', 'now'));
            // Forzamos a Domingo
            if ($date->dayOfWeek !== \Illuminate\Support\Carbon::SUNDAY) {
                $date = $date->next(\Illuminate\Support\Carbon::SUNDAY);
            }
            \App\Models\Invoice::factory()->create([
                'issue_date' => $date,
                'invoice_number' => 'SUNDAY-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                'vendor_id' => $vendors->random()->id,
                'file_path' => 'invoices/dummy.pdf',
            ]);
        }
    }
}
