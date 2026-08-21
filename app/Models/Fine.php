<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'borrowing_id',
        'rate_per_day',
        'overdue_days',
        'amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'rate_per_day' => 'decimal:2',
            'amount' => 'decimal:2',
            'overdue_days' => 'integer',
        ];
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
