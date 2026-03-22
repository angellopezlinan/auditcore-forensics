<?php

namespace App\Filament\Widgets;

use App\Models\Incident;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s'; 

    protected function getStats(): array
    {
        $tenantId = filament()->getTenant()->getKey();
        
        $incidentesAbiertos = Incident::where('team_id', $tenantId)
                                ->whereIn('status', ['open', 'investigating'])
                                ->count();
        
        return [
            Stat::make('Auditorías en Curso', $incidentesAbiertos)
                ->description($incidentesAbiertos > 0 ? 'Hallazgos pendientes de revisión' : 'Sistema en cumplimiento total')
                ->descriptionIcon($incidentesAbiertos > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-shield-check')
                ->color($incidentesAbiertos > 0 ? 'danger' : 'success')
                ->chart($incidentesAbiertos > 0 ? [0, 1, 2, 4, 3, 5] : []),
        ];
    }
}
