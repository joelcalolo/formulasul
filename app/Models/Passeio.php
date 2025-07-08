<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passeio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'subtitulo',
        'imagem_principal',
        'localizacao',
        'duracao',
        'avaliacao',
        'total_avaliacoes',
        'descricao',
        'preco',
        'tamanho_grupo',
        'idioma',
        'galeria'
    ];

    protected $casts = [
        'galeria' => 'array',
        'preco' => 'decimal:2',
        'avaliacao' => 'decimal:1',
        'total_avaliacoes' => 'integer'
    ];
} 