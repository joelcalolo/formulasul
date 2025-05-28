<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'carro_id',
        'data_inicio',
        'data_fim',
        'local_entrega',
        'pago',
        'codigo_confirmacao',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'carro_id');
    }
}