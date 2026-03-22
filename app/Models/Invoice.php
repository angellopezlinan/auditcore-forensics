<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'vendor_id',
        'invoice_number',
        'issue_date',
        'total_amount',
        'pdf_vault_path',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'total_amount' => 'decimal:2',
        'status' => InvoiceStatus::class,
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function fraudAlerts(): HasMany
    {
        return $this->hasMany(FraudAlert::class);
    }
}
