<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos
    protected $fillable = [
        'nome',
        'data',
        'hora_inicio',
        'hora_fim',
        'descricao',
        'status',
        'user_id'
    ];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Método mágico para exibir o nome do agendamento
    public function __toString(): string
    {
        return "{$this->nome} - {$this->data}";
    }
}
