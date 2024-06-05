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
        Schema::create('trans_sasaran_indikator', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_klp_id')->nullable()->index();
            $table->unsignedBigInteger('trans_sasaran_id')->nullable()->index();
            $table->string('kode', 50);
            $table->string('nama', 200);
            $table->integer('target_kinerja')->nullable();
            $table->string('satuan')->nullable();
            $table->boolean('nsa_indikator_kinerja_tepat')->nullable();
            $table->boolean('nsa_target_kinerja_tepat')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->foreign('trans_sasaran_id')
                ->references('id')->on('trans_sasaran')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('ref_klp_id')
                ->references('id')->on('ref_klps')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_sasaran_indikator');
    }
};
