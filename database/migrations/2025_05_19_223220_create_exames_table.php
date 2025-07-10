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
        Schema::create('exames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->foreignId('consulta_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('medico_id')->constrained()->onDelete('cascade');
            $table->string('tipo_exame');
            $table->text('descricao')->nullable();
            $table->date('data_solicitacao');
            $table->date('data_realizacao')->nullable();
            $table->text('resultado')->nullable();
            $table->text('observacoes')->nullable();
            $table->string('arquivo_resultado')->nullable(); // Caminho para o arquivo
            $table->enum('status', ['solicitado', 'agendado', 'realizado', 'cancelado'])->default('solicitado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exames');
    }
};
