<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laudo extends Model
{
    use HasFactory;

    protected $fillable = ['aluno_id', 'data', 'descricao', 'criado_por'];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
