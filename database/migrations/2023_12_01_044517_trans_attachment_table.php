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
        Schema::create('trans_attachment', function (Blueprint $table) {
            $table->id();
            $table->string('parent_table');
            $table->unsignedBigInteger('table_id');
            $table->string('path');
            $table->string('name');
            $table->integer('size');
            $table->string('extension');
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
    public function down()
    {
        Schema::dropIfExists('trans_attachment');
    }
};
