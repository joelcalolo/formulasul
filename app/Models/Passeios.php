<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passeios extends Model
{
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
        'galeria' => 'array'
    ];
}
