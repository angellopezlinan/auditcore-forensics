<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BankAccount extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'iban_hash',
        'swift',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bankable(): MorphTo
    {
        return $this->morphTo();
    }
}
