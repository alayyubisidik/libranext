<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'fine_id',
        'user_id',
        'order_id',
        'transaction_id',
        'method',
        'payment_type',
        'amount',
        'status',
        'paid_at',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'raw_response' => 'array',
        ];
    }

    public function fine()
    {
        return $this->belongsTo(Fine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
