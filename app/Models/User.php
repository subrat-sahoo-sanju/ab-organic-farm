<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phone',
        'password',
        'dob',
        'gender',
        'avatar_path',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'dob' => 'date',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /* ---------------- RBAC ---------------- */

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string|UserRole ...$roles): bool
    {
        $names = array_map(
            fn ($r) => $r instanceof UserRole ? $r->value : $r,
            $roles
        );

        return $this->roles->pluck('name')->intersect($names)->isNotEmpty();
    }

    public function hasAnyRole(array $names): bool
    {
        return $this->roles->pluck('name')->intersect($names)->isNotEmpty();
    }

    public function isStaff(): bool
    {
        return $this->hasAnyRole(UserRole::staffRoles());
    }

    public function primaryRole(): ?string
    {
        return $this->roles->first()?->name;
    }

    /* ---------------- Commerce ---------------- */

    public function deliveryPerson()
    {
        return $this->hasOne(DeliveryPerson::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }
}
