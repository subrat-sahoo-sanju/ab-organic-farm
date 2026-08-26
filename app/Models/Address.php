<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'name', 'phone',
        'house_no', 'street', 'area', 'landmark', 'city', 'state', 'pincode',
        'is_default',
    ];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oneLine(): string
    {
        return collect([
            $this->house_no,
            $this->street,
            $this->area,
            $this->landmark,
            $this->city,
            $this->state.' - '.$this->pincode,
        ])->filter()->implode(', ');
    }
}
