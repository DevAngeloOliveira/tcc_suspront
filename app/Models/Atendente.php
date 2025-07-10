<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Atendente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'email',
        'registro',
        'user_id',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Obtém o usuário associado ao atendente.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
