<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('trans_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('klp_id');
            $table->unsignedBigInteger('penilaian_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', ['AKTIF', 'NON AKTIF']);
            $table->timestamps();

            $table->foreign('klp_id')
            ->references('id')
                ->on('ref_klps')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('penilaian_id')
            ->references('id')
                ->on('trans_penilaian')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
            ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('trans_invitations');
    }
};

