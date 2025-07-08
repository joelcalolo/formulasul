<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca', 'modelo', 'caixa', 'tracao', 'lugares', 'combustivel', 'status', 
        'image_cover', 'image_1', 'image_2', 'image_3', 'has_gallery', 'price',
        'cor', 'transmissao', 'descricao'
    ];

    protected $casts = [
        'has_gallery' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function priceTable()
    {
        return $this->hasOne(PriceTable::class);
    }

    public function isAvailable($startDate, $endDate)
    {
        // Verifica se o carro está com status disponível
        if ($this->status !== 'disponivel') {
            return false;
        }

        // Converte as datas para objetos Carbon
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Verifica se não há reservas confirmadas para este período
        $conflictingRentals = RentalRequest::where('carro_principal_id', $this->id)
            ->where('status', 'confirmado')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('data_inicio', [$start, $end])
                    ->orWhereBetween('data_fim', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('data_inicio', '<=', $start)
                            ->where('data_fim', '>=', $end);
                    });
            })
            ->count();

        return $conflictingRentals === 0;
    }

    public function getAvailableDates($monthsAhead = 3)
    {
        $dates = [];
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addMonths($monthsAhead)->endOfDay();

        // Obtém todas as reservas confirmadas neste período
        $confirmedRentals = RentalRequest::where('carro_principal_id', $this->id)
            ->where('status', 'confirmado')
            ->where('data_inicio', '<=', $endDate)
            ->where('data_fim', '>=', $startDate)
            ->get();

        // Cria um array com todos os dias do período
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $isAvailable = true;
            foreach ($confirmedRentals as $rental) {
                if ($currentDate->between($rental->data_inicio, $rental->data_fim)) {
                    $isAvailable = false;
                    break;
                }
            }
            $dates[$currentDate->format('Y-m-d')] = $isAvailable;
            $currentDate->addDay();
        }

        return $dates;
    }

    /**
     * Obter a imagem principal (capa)
     */
    public function getCoverImageAttribute()
    {
        return $this->image_cover ? asset('storage/' . $this->image_cover) : asset('images/car-placeholder.jpg');
    }

    /**
     * Obter todas as imagens da galeria
     */
    public function getGalleryImagesAttribute()
    {
        $images = [];
        
        // Adicionar imagem de capa se existir
        if ($this->image_cover) {
            $images[] = [
                'url' => asset('storage/' . $this->image_cover),
                'type' => 'cover',
                'alt' => $this->marca . ' ' . $this->modelo . ' - Imagem Principal'
            ];
        }
        
        // Adicionar outras imagens se existirem
        for ($i = 1; $i <= 3; $i++) {
            $imageField = "image_{$i}";
            if ($this->$imageField) {
                $images[] = [
                    'url' => asset('storage/' . $this->$imageField),
                    'type' => 'gallery',
                    'alt' => $this->marca . ' ' . $this->modelo . ' - Imagem ' . $i
                ];
            }
        }
        
        return $images;
    }

    /**
     * Obter apenas as imagens da galeria (excluindo a capa)
     */
    public function getAdditionalImagesAttribute()
    {
        $images = [];
        
        for ($i = 1; $i <= 3; $i++) {
            $imageField = "image_{$i}";
            if ($this->$imageField) {
                $images[] = [
                    'url' => asset('storage/' . $this->$imageField),
                    'type' => 'gallery',
                    'alt' => $this->marca . ' ' . $this->modelo . ' - Imagem ' . $i
                ];
            }
        }
        
        return $images;
    }

    /**
     * Verificar se o carro tem galeria de imagens
     */
    public function hasGallery()
    {
        return $this->image_cover || $this->image_1 || $this->image_2 || $this->image_3;
    }

    /**
     * Obter o número total de imagens
     */
    public function getImageCountAttribute()
    {
        $count = 0;
        
        if ($this->image_cover) $count++;
        if ($this->image_1) $count++;
        if ($this->image_2) $count++;
        if ($this->image_3) $count++;
        
        return $count;
    }

    /**
     * Obter a primeira imagem disponível (capa ou placeholder)
     */
    public function getFirstImageAttribute()
    {
        if ($this->image_cover) {
            return asset('storage/' . $this->image_cover);
        }
        
        // Se não tem capa, usar a primeira imagem da galeria
        for ($i = 1; $i <= 3; $i++) {
            $imageField = "image_{$i}";
            if ($this->$imageField) {
                return asset('storage/' . $this->$imageField);
            }
        }
        
        return asset('images/car-placeholder.jpg');
    }
}
