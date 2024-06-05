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
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->index();
            $table->unsignedBigInteger('permission_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('permission_id')
                ->references('id')->on('permissions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
    }
};