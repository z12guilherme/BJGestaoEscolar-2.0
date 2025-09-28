<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'year', 'professor_id', 'escola_id'];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }

    public function professores()
    {
        return $this->belongsToMany(Professor::class, 'professor_turma');
    }
}
