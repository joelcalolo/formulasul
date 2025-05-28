<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'origem',
        'destino',
        'data_hora',
        'tipo',
        'observacoes',
        'status',
        'email_enviado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}