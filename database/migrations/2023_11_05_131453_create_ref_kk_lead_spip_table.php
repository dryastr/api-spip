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
        Schema::create('ref_kk_lead_spip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->unsignedBigInteger('ref_jenis_kk_lead_spip_id')->nullable()->index();
            $table->string('kode', 50)->nullable()->index();
            $table->string('nama')->nullable();
            $table->decimal('bobot')->default(0)->nullable();
            $table->enum('jenis_klp', ['PEMDA', 'KL'])->nullable();
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->foreign('ref_jenis_kk_lead_spip_id')
                ->references('id')->on('ref_jenis_kk_lead_spip')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_kk_lead_spip');
    }
};
