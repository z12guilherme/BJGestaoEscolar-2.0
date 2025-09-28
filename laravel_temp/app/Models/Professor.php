<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'subject', 'user_id', 'escola_id', 'telefone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'professor_turma');
    }

    public function laudos()
    {
        return $this->hasMany(Laudo::class, 'criado_por', 'user_id');
    }
}
