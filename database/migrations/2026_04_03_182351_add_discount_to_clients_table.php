<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Скидка клиента в процентах (0–100), применяется при расчёте выручки
            $table->unsignedTinyInteger('discount')->default(0)->after('salon_id');
            // Внутренние заметки о клиенте (для администратора)
            $table->text('notes')->nullable()->after('discount');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['discount', 'notes']);
        });
    }
};
