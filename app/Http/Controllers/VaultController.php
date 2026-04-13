<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VaultController extends Controller
{
    use AuthorizesRequests;

    /**
     * Descarga segura de la factura desde la bóveda privada.
     * Verifica autorización Multi-Tenant y existencia del archivo.
     */
    public function downloadInvoice(Invoice $invoice): \Symfony\Component\HttpFoundation\Response
    {
        $this->authorize('view', $invoice);

        $path = $invoice->file_path;

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404, 'El archivo solicitado no se encuentra en la bóveda privada.');
        }

        return Storage::disk('private')->download($path, "Factura_{$invoice->invoice_number}.pdf");
    }
}
