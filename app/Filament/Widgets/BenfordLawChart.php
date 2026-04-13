<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

class BenfordLawChart extends ChartWidget
{
    protected static ?string $heading = '📈 Análisis de Fraude: Ley de Benford (Importes)';
    
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $tenantId = Filament::getTenant()->id;
        
        // Benford's Law Theoretical Distribution (Digits 1-9)
        $benfordTheoretical = [30.1, 17.6, 12.5, 9.7, 7.9, 6.7, 5.8, 5.1, 4.6];

        // Fetch invoice amounts for the current tenant and extract first significant digit
        // We skip 0 and empty amounts.
        $amounts = Invoice::query()
            ->where('team_id', $tenantId)
            ->where('total_amount', '>', 0)
            ->pluck('total_amount');

        $counts = array_fill(1, 9, 0);
        $totalCount = 0;

        foreach ($amounts as $amount) {
            // Remove symbols and leading zeros/dots to find the first significant digit
            $cleanAmount = ltrim(preg_replace('/[^0-9]/', '', (string)$amount), '0');
            
            if (strlen($cleanAmount) > 0) {
                $firstDigit = (int)$cleanAmount[0];
                if ($firstDigit >= 1 && $firstDigit <= 9) {
                    $counts[$firstDigit]++;
                    $totalCount++;
                }
            }
        }

        $realPercentages = [];
        if ($totalCount > 0) {
            foreach (range(1, 9) as $digit) {
                $realPercentages[] = round(($counts[$digit] / $totalCount) * 100, 1);
            }
        } else {
            $realPercentages = array_fill(0, 9, 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Distribución Real',
                    'data' => $realPercentages,
                    'borderColor' => '#3b82f6', // Corporate Blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Curva Benford (Teórica)',
                    'data' => $benfordTheoretical,
                    'borderColor' => '#94a3b8', // Gray/Neutral
                    'borderDash' => [5, 5], // Dotted line
                    'fill' => false,
                    'tension' => 0.4,
                ],
            ],
            'labels' => ['1', '2', '3', '4', '5', '6', '7', '8', '9'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
