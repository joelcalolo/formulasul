<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class RentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'carro_principal_id',
        'carro_secundario_id',
        'data_inicio',
        'data_fim',
        'local_entrega',
        'observacoes',
        'status',
        'email_enviado',
        'reject_reason',
    ];

    protected $dates = ['data_inicio', 'data_fim']; // Converte automaticamente para Carbon

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'carro_principal_id');
    }

    public function carroPrincipal()
    {
        return $this->belongsTo(Car::class, 'carro_principal_id');
    }

    public function carroSecundario()
    {
        return $this->belongsTo(Car::class, 'carro_secundario_id');
    }
}