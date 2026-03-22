<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FraudAlertType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraudAlert extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'invoice_id',
        'type',
        'severity_score',
        'evidence_metadata',
        'is_resolved',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'type' => FraudAlertType::class,
        'severity_score' => 'integer',
        'evidence_metadata' => 'array',
        'is_resolved' => 'boolean',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
