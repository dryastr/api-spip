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
        Schema::create('auth_tokens', function (Blueprint $table) {
            $table->string('token')->nullable()->default(null)->index();
            $table->string('fullname')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->unsignedInteger('user_id')->nullable()->default(null);
            $table->integer('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_tokens');
    }
};
