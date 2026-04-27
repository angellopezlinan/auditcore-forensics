<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->teams()->exists();
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->belongsToInvoiceTeam($user, $invoice);
    }

    public function create(User $user): bool
    {
        return $user->teams()->exists();
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $this->belongsToInvoiceTeam($user, $invoice);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $this->belongsToInvoiceTeam($user, $invoice);
    }

    public function restore(User $user, Invoice $invoice): bool
    {
        return $this->belongsToInvoiceTeam($user, $invoice);
    }

    public function forceDelete(User $user, Invoice $invoice): bool
    {
        return $this->belongsToInvoiceTeam($user, $invoice);
    }

    public function download(User $user, Invoice $invoice): bool
    {
        return $this->belongsToInvoiceTeam($user, $invoice);
    }

    public function export(User $user): bool
    {
        return $user->teams()->exists();
    }

    private function belongsToInvoiceTeam(User $user, Invoice $invoice): bool
    {
        return $user->teams()->whereKey($invoice->team_id)->exists();
    }
}
