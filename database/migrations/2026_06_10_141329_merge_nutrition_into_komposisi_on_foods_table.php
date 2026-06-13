<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->string('komposisi')->nullable()->after('harga');
        });

        DB::statement("UPDATE foods SET komposisi = CONCAT('Kalori: ', kalori, ' kkal, Gula: ', gula, ' g, Lemak: ', lemak, ' g')");

        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn(['kalori', 'gula', 'lemak']);
        });
    }

    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->integer('kalori')->default(0)->after('harga');
            $table->integer('gula')->default(0)->after('kalori');
            $table->integer('lemak')->default(0)->after('gula');
        });

        DB::statement("UPDATE foods SET kalori = 0, gula = 0, lemak = 0");

        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn('komposisi');
        });
    }
};
