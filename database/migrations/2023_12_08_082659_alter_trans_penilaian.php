<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trans_penilaian', function (Blueprint $table) {
            $table->enum('asal_data', ['input', 'interop'])->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_penilaian', function (Blueprint $table) {
            $table->dropColumn('asal_data');
        });
    }
};
