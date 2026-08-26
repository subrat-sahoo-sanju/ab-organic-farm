<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodCollection extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'payment_id', 'collected_by', 'collector_type',
        'amount', 'collected_at', 'notes', 'receipt_ref',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'collected_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
