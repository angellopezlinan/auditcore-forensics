<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    /**
     * Determine whether the user can view the invoice.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        // En AuditCore, verificamos si el usuario pertenece al equipo de la factura (Multi-Tenant).
        // Se utiliza exists() para mayor eficiencia en la relación BelongsToMany.
        return $user->teams()->where('teams.id', $invoice->team_id)->exists();
    }

    /**
     * Determine whether the user can create invoices.
     */
    public function create(User $user): bool
    {
        return true; 
    }

    /**
     * Determine whether the user can update the invoice.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        return $user->teams()->where('teams.id', $invoice->team_id)->exists();
    }

    /**
     * Determine whether the user can delete the invoice.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->teams()->where('teams.id', $invoice->team_id)->exists();
    }
}
