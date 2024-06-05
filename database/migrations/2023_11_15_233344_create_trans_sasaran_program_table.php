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
        Schema::create('trans_sasaran_program', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_klp_id');
            $table->unsignedBigInteger('trans_penilaian_id');
            $table->unsignedBigInteger('trans_program_id');
            $table->unsignedBigInteger('trans_sasaran_id');
            $table->unsignedBigInteger('trans_sasaran_indikator_id');
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->foreign('trans_penilaian_id')
                ->references('id')
                ->on('trans_penilaian')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('ref_klp_id')
                ->references('id')->on('ref_klps')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('trans_program_id')
                ->references('id')
                ->on('trans_program')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('trans_sasaran_id')
                ->references('id')
                ->on('trans_sasaran')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('trans_sasaran_indikator_id')
                ->references('id')
                ->on('trans_sasaran_indikator')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_sasaran_program');
    }
};
