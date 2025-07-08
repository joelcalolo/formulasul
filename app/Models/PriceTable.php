<?php
// app/Models/PriceTable.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'preco_dentro_com_motorista',
        'preco_dentro_sem_motorista',
        'preco_fora_com_motorista',
        'preco_fora_sem_motorista',
        'taxa_entrega_recolha',
        'plafond_km_dia',
        'preco_km_extra',
        'caucao'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}