<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role',
        'phone' // Adicione este campo
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    public function rentalRequests()
    {
        return $this->hasMany(RentalRequest::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    // Método para verificar se é admin
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}