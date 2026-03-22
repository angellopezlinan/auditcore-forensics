<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorDocument extends Model
{
    protected $fillable = [
        'team_id',
        'document_type',
        'name',
        'file_path',
        'expiration_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}
