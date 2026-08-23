<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Fine extends Model
{
    use LogsActivity;

    protected $fillable = [
        'borrowing_id',
        'rate_per_day',
        'overdue_days',
        'amount',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

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
