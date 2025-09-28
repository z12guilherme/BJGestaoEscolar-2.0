<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'turma_id', 'user_id'];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laudos()
    {
        return $this->hasMany(Laudo::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
