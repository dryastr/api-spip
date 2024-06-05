<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE ref_klps MODIFY COLUMN jenis ENUM('PS', 'KL', 'PEMDA', 'OPD')");
        Schema::table('ref_klps', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_lokasi_kabkot_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
