<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocIaInteraction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tenant_id',
        'type',
        'payload',
        'response',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Get the tenant that owns the interaction log.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
