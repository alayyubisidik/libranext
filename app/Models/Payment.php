<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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
