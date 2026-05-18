<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'nomor_telepon',
        'status_user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function keluhans()
    {
        return $this->hasMany(Keluhan::class, 'created_by');
    }

    public function ticketsSebagaiTeknisi()
    {
        return $this->hasMany(Ticket::class, 'teknisi_id');
    }

    public function ticketsAssigned()
    {
        return $this->hasMany(Ticket::class, 'assigned_by');
    }

    public function ticketsVerified()
    {
        return $this->hasMany(Ticket::class, 'verified_by');
    }
}