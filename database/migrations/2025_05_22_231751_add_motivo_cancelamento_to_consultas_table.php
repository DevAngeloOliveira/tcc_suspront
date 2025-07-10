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
        Schema::table('consultas', function (Blueprint $table) {
            $table->text('motivo_cancelamento')->nullable()->after('status');
            $table->foreignId('cancelado_por')->nullable()->after('motivo_cancelamento')->constrained('users')->nullOnDelete();
            $table->timestamp('notificado_em')->nullable()->after('cancelado_por');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropForeign(['cancelado_por']);
            $table->dropColumn(['motivo_cancelamento', 'cancelado_por', 'notificado_em']);
        });
    }
};
