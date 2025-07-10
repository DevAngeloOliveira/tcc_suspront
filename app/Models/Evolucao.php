<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evolucao extends Model
{
    use HasFactory;

    protected $table = 'evolucoes';

    protected $fillable = [
        'prontuario_id',
        'medico_id',
        'descricao',
    ];

    /**
     * Get the prontuário that owns the evolução.
     */
    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class);
    }

    /**
     * Get the médico relacionado à evolução.
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
}
