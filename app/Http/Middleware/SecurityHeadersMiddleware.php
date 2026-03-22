<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Eliminar cabeceras informativas del servidor
        if (!app()->runningInConsole()) {
            header_remove('X-Powered-By');
        }
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Cabeceras de Seguridad
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy (Ajustada para Filament)
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://maps.googleapis.com; ";
        $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net; ";
        $csp .= "img-src 'self' data: https://ui-avatars.com https://maps.gstatic.com https://*.googleapis.com; ";
        $csp .= "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net; ";
        $csp .= "frame-ancestors 'self'; ";
        $csp .= "upgrade-insecure-requests;";
        
        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS en Producción
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31104000; includeSubDomains');
        }

        return $response;
    }
}
