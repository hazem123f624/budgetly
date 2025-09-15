<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receivable extends Model
{
    protected $fillable = [
        'user_id',
        'source',
        'description',
        'amount',
        'date',
        'status',
        'paid_amount',
        'remaining_amount',
        'notes',
        'currency',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
