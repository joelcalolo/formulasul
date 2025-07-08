<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'origem',
        'destino',
        'data_hora',
        'tipo',
        'observacoes',
        'status',
        'email_enviado',
        'flight_number',
        'flight_time',
        'flight_date',
        'airline',
        'special_requests',
        'num_pessoas',
        'confirmed_at',
        'rejected_at',
        'admin_notes',
        'confirmation_method',
        'notification_sent',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        'confirmed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'notification_sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function getStatusOptions()
    {
        return [
            'pendente' => 'Pendente',
            'confirmado' => 'Confirmado',
            'rejeitado' => 'Rejeitado'
        ];
    }

    public function getStatusColorClass()
    {
        return [
            'pendente' => 'bg-yellow-100 text-yellow-800',
            'confirmado' => 'bg-green-100 text-green-800',
            'rejeitado' => 'bg-red-100 text-red-800'
        ][$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}