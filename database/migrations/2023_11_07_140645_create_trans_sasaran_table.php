<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trans_sasaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('ref_klp_id')->nullable()->index();
            $table->unsignedBigInteger('ref_jenis_sasaran_id')->nullable();
            $table->unsignedBigInteger('trans_penilaian_id')->nullable();
            $table->string('kode', 50);
            $table->string('nama', 200);
            $table->boolean('nsa_orientasi_hasil')->nullable();
            $table->boolean('nsa_relevan_mandat_sa')->nullable();
            $table->boolean('nsa_uji_kecukupan_indikator_sa')->nullable();
            $table->text('catatan')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->foreign('ref_klp_id')
                ->references('id')->on('ref_klps')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('ref_jenis_sasaran_id')
                ->references('id')->on('ref_jenis_sasaran')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('trans_penilaian_id')
                ->references('id')->on('trans_penilaian')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_sasaran');
    }
};
