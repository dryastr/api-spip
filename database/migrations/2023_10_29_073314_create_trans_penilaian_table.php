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
        Schema::create('trans_penilaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ref_klp_id')->nullable()->index();
            $table->integer('tahun')->index();
            $table->decimal('anggaran', 12, 2)->index();
            $table->enum('status', ['draft', 'preparation', 'open-self-assessment', 'open-verification-assessment', 'final'])->default('draft')->index();
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('trans_penilaian');
    }
};
