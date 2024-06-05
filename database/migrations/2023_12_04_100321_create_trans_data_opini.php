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
        Schema::create('trans_data_opini', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_klp_id')->nullable()->index();
            $table->unsignedBigInteger('trans_penilaian_id')->nullable()->index();
            $table->integer('tahun');
            $table->string('opini');
            $table->integer('persentase_bmn')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->foreign('trans_penilaian_id')
                    ->references('id')->on('trans_penilaian')
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
        Schema::dropIfExists('trans_data_opini');
    }
};
