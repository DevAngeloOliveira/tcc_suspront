<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'mensagem',
        'dados_extras',
        'lida',
        'lida_em'
    ];

    protected $casts = [
        'lida' => 'boolean',
        'lida_em' => 'datetime',
        'dados_extras' => 'array'
    ];

    /**
     * Obtém o usuário associado à notificação
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marca a notificação como lida
     */
    public function marcarComoLida()
    {
        $this->lida = true;
        $this->lida_em = now();
        $this->save();

        return $this;
    }

    /**
     * Escopo de consulta para notificações não lidas
     */
    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }
}
