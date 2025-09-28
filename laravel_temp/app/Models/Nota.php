<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = ['aluno_id', 'disciplina', 'nota', 'data_avaliacao'];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}
