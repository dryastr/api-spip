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
        DB::statement("ALTER TABLE registrations MODIFY COLUMN jenis ENUM('PS', 'KL', 'PEMDA', 'OPD')");
        Schema::table('registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_lokasi_id')->nullable();
            $table->unsignedBigInteger('ref_lokasi_kabkot_id')->nullable();
            $table->string('nama_dinas')->nullable();
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
