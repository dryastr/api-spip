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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->index()->nullable();
            $table->string('email')->index();
            $table->string('fullname', 150)->index();
            $table->string('mobile', 50)->nullable();
            $table->string('username')->nullable()->index();
            $table->string('password');
            $table->string('email_verify_key')->index()->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->string('remember_token')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
