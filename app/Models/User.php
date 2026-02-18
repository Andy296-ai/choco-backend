<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'login',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'salon_id',
    ];

    const ROLE_SPECIALIST = 'specialist';
    const ROLE_ADMIN = 'admin';
    const ROLE_DIRECTOR = 'director';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function specialistBookings()
    {
        return $this->hasMany(Booking::class, 'specialist_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function portfolioItems()
    {
        return $this->hasMany(PortfolioItem::class);
    }

    public function isDirector() { return $this->role === self::ROLE_DIRECTOR; }
    public function isAdmin() { return $this->role === self::ROLE_ADMIN; }
    public function isSpecialist() { return $this->role === self::ROLE_SPECIALIST; }
}