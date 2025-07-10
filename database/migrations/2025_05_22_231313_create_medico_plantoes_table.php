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
        Schema::create('medico_plantoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained('medicos')->onDelete('cascade');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable(); // Null para plantões de um único dia
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->enum('dia_semana', ['1', '2', '3', '4', '5', '6', '7'])->nullable(); // 1=Domingo, 7=Sábado
            $table->boolean('recorrente')->default(false); // Se é um plantão semanal recorrente
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->integer('capacidade_consultas')->default(10); // Número máximo de consultas por plantão
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medico_plantoes');
    }
};
