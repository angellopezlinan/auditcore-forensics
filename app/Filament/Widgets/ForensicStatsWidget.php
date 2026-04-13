<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

class ForensicStatsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $tenantId = Filament::getTenant()->id;

        // Stat 1: Volumen Auditado (Total)
        $totalInvoices = Invoice::query()
            ->where('team_id', $tenantId)
            ->count();

        // Stat 2: Posibles Duplicados (Mismo vendor e importe)
        $duplicateCount = Invoice::query()
            ->where('team_id', $tenantId)
            ->whereExists(function ($query) use ($tenantId) {
                $query->select(DB::raw(1))
                    ->from('invoices as i2')
                    ->whereColumn('i2.vendor_id', 'invoices.vendor_id')
                    ->whereColumn('i2.total_amount', 'invoices.total_amount')
                    ->whereColumn('i2.id', '!=', 'invoices.id')
                    ->where('i2.team_id', $tenantId);
            })
            ->count();

        // Stat 3: Importes Sospechosos (Redondos, múltiplos de 1.000)
        // Usamos MOD en la base de datos para eficiencia
        $roundAmountsCount = Invoice::query()
            ->where('team_id', $tenantId)
            ->whereRaw('MOD(total_amount, 1000) = 0')
            ->where('total_amount', '>', 0)
            ->count();

        return [
            Stat::make('Volumen Auditado', $totalInvoices)
                ->description('Total de facturas en el sistema')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),
            
            Stat::make('Posibles Duplicados', $duplicateCount)
                ->description('Facturas con coincidencias exactas')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Importes Sospechosos', $roundAmountsCount)
                ->description('Cifras redondas inusuales (k€)')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('warning'),
        ];
    }
}
