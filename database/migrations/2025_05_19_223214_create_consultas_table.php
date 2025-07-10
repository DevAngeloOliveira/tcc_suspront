<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->foreignId('medico_id')->constrained()->onDelete('cascade');
            $table->foreignId('prontuario_id')->constrained()->onDelete('cascade');
            $table->dateTime('data_hora');
            $table->string('tipo_consulta'); // Exemplo: Rotina, Urgência, Retorno
            $table->text('queixa_principal')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('prescricao')->nullable();
            $table->text('observacoes')->nullable();
            $table->enum('status', ['agendada', 'confirmada', 'em_andamento', 'concluida', 'cancelada'])->default('agendada');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
