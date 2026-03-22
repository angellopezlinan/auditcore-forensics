<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;

class SyncSpatieTenant
{
    public function handle(Request $request, Closure $next)
    {
        // Filament v3: Obtenemos la Entidad actual. Si existe, le pasamos el ID a Spatie.
        if ($tenant = Filament::getTenant()) {
            setPermissionsTeamId($tenant->getKey());
        }

        return $next($request);
    }
}
