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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('ref_klp_id')->nullable()->index();
            $table->enum('jenis', ['KL', 'PEMDA']);
            $table->string('ref_klp_nama')->nullable();
            $table->string('no_telp', 50)->nullable();
            $table->string('website', 50)->nullable();
            $table->string('fax', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('is_admin')->default(false)->nullable();
            $table->string('file_surat_permohonan')->nullable();
            $table->enum('status_approval', ['dalam-antrian', 'disetujui', 'ditolak']);
            $table->dateTime('approval_at')->nullable();
            $table->integer('approval_by')->nullable();
            $table->string('approval_by_name')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('registrations');
    }
};
