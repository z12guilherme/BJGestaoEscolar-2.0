<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'endereco'];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function professores()
    {
        return $this->hasMany(Professor::class);
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }
}
